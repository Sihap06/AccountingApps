<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Recomputes the profit figures frozen inside pending_changes.new_data for the
 * rows repaired by transaction:repair-profit, so the verification screen stops
 * showing numbers that contradict the transaction it describes.
 *
 * Each snapshot is recomputed from its OWN biaya rather than copied from the
 * live row, because a record may have been edited again afterwards.
 */
class SyncPendingChangeSnapshots extends Command
{
    protected $signature = 'transaction:sync-pending-snapshots
                            {--apply : Write the corrected snapshots to the database}
                            {--backup= : Path for the JSON backup of the original snapshots}';

    protected $description = 'Recalculate modal/fee_teknisi/untung inside pending_changes.new_data for repaired rows';

    /**
     * Rows corrected by transaction:repair-profit. Snapshots of any other row
     * are left alone, so a snapshot is never made to disagree with the
     * transaction it belongs to.
     */
    private const REPAIRED = [
        'App\Models\Transaction' => [5376, 5917, 5926, 5941, 6021, 6078, 6079, 6085, 6123, 6164, 6199, 6205, 6263, 6265, 6315],
        'App\Models\TransactionItem' => [503],
    ];

    public function handle()
    {
        $apply = (bool) $this->option('apply');
        $technicians = DB::table('technicians')->get()->keyBy('id');

        $updates = [];
        $skipped = [];

        foreach (self::REPAIRED as $model => $ids) {
            $snapshots = DB::table('pending_changes')
                ->where('changeable_type', $model)
                ->whereIn('changeable_id', $ids)
                ->orderBy('id')
                ->get();

            foreach ($snapshots as $snapshot) {
                $new = json_decode($snapshot->new_data, true);
                if (!is_array($new) || !isset($new['modal'], $new['fee_teknisi'], $new['biaya'])) {
                    continue;
                }
                if (empty($new['product_id']) || empty($new['technical_id'])) {
                    continue;
                }
                if (!isset($technicians[$new['technical_id']])) {
                    continue;
                }

                $technician = $technicians[$new['technical_id']];
                $effectiveBiaya = (int) $new['biaya'] - (int) ($new['potongan'] ?? 0);

                $context = [
                    'pc' => $snapshot->id,
                    'model' => class_basename($model),
                    'id' => $snapshot->changeable_id,
                    'biaya' => $effectiveBiaya,
                ];

                $carriesSignature = (int) $new['modal'] === (int) $new['fee_teknisi']
                    && (int) round($effectiveBiaya * $technician->percent_fee / 100) === (int) $new['fee_teknisi'];

                if (!$carriesSignature) {
                    // A snapshot describing exactly the configuration the row
                    // holds now must report the row's own figures, whatever
                    // shape its wrongness took.
                    $aligned = $this->alignWithLiveRow($model, $snapshot->changeable_id, $new);
                    if ($aligned !== null) {
                        $updates[] = $context + [
                            'sparepart' => 0,
                            'original_json' => $snapshot->new_data,
                            'payload' => $aligned,
                        ];
                    }
                    continue;
                }

                $sparepartPrice = $this->recoverHistoricalSparepartPrice($model, $snapshot->changeable_id, $new['product_id']);
                if ($sparepartPrice === null) {
                    $skipped[] = $context + ['reason' => 'no usable historical price'];
                    continue;
                }
                if ($effectiveBiaya <= $sparepartPrice) {
                    $skipped[] = $context + ['reason' => 'cost <= sparepart price'];
                    continue;
                }

                $fee = ($effectiveBiaya - $sparepartPrice) * $technician->percent_with_sparepart / 100;

                $new['modal'] = (int) round($sparepartPrice + $fee);
                $new['fee_teknisi'] = (int) round($fee);
                $new['untung'] = (int) round($effectiveBiaya - $sparepartPrice - $fee);

                $updates[] = $context + [
                    'sparepart' => $sparepartPrice,
                    'original_json' => $snapshot->new_data,
                    'payload' => $new,
                ];
            }
        }

        $this->renderReport($updates, $skipped);

        if (empty($updates)) {
            $this->info('Nothing to sync.');
            return 0;
        }

        if (!$apply) {
            $this->newLine();
            $this->warn('DRY RUN - nothing was written. Re-run with --apply to commit these changes.');
            return 0;
        }

        $backupPath = $this->option('backup')
            ?: storage_path('app/pending-snapshots-backup-' . date('Ymd-His') . '.json');
        $this->writeBackup($backupPath, $updates);
        $this->info('Backup of the original snapshots written to: ' . $backupPath);

        DB::transaction(function () use ($updates) {
            foreach ($updates as $update) {
                DB::table('pending_changes')
                    ->where('id', $update['pc'])
                    ->update(['new_data' => json_encode($update['payload'])]);
            }
        });

        $this->info('Synced ' . count($updates) . ' snapshot(s).');

        return 0;
    }

    /**
     * Copies the live row's profit figures into a snapshot that already
     * describes the same cost, discount, sparepart and technician. Nothing is
     * invented: the snapshot and the row refer to one and the same state.
     * Returns null when the snapshot describes a different state, or already
     * agrees with the row.
     */
    private function alignWithLiveRow(string $model, $id, array $new)
    {
        $table = $model === 'App\Models\TransactionItem' ? 'transaction_items' : 'transactions';
        $row = DB::table($table)->where('id', $id)->first();
        if (!$row) {
            return null;
        }

        $sameState = (int) $new['biaya'] === (int) $row->biaya
            && (int) ($new['potongan'] ?? 0) === (int) ($row->potongan ?? 0)
            && (string) $new['product_id'] === (string) $row->product_id
            && (string) $new['technical_id'] === (string) $row->technical_id;

        if (!$sameState) {
            return null;
        }

        if ((int) $new['modal'] === (int) $row->modal
            && (int) $new['fee_teknisi'] === (int) $row->fee_teknisi
            && (int) ($new['untung'] ?? 0) === (int) $row->untung) {
            return null;
        }

        $new['modal'] = (int) $row->modal;
        $new['fee_teknisi'] = (int) $row->fee_teknisi;
        $new['untung'] = (int) $row->untung;

        return $new;
    }

    private function recoverHistoricalSparepartPrice(string $model, $id, $productId)
    {
        $snapshots = DB::table('pending_changes')
            ->where('changeable_type', $model)
            ->where('changeable_id', $id)
            ->whereNotNull('old_data')
            ->orderByDesc('id')
            ->get();

        foreach ($snapshots as $snapshot) {
            $old = json_decode($snapshot->old_data, true);
            if (!is_array($old) || !isset($old['modal'], $old['fee_teknisi'])) {
                continue;
            }
            if (!isset($old['product_id']) || (string) $old['product_id'] !== (string) $productId) {
                continue;
            }

            $price = (int) $old['modal'] - (int) $old['fee_teknisi'];
            if ($price > 0) {
                return $price;
            }
        }

        return null;
    }

    private function renderReport(array $updates, array $skipped)
    {
        if ($updates) {
            $this->info('Snapshots to sync: ' . count($updates));
            $this->table(
                ['PC', 'Type', 'Row', 'Cost', 'Sparepart', 'Modal', 'Fee', 'Profit'],
                array_map(function ($u) {
                    $before = json_decode($u['original_json'], true);
                    return [
                        $u['pc'],
                        $u['model'],
                        $u['id'],
                        number_format($u['biaya']),
                        number_format($u['sparepart']),
                        number_format($before['modal']) . ' -> ' . number_format($u['payload']['modal']),
                        number_format($before['fee_teknisi']) . ' -> ' . number_format($u['payload']['fee_teknisi']),
                        number_format($before['untung'] ?? 0) . ' -> ' . number_format($u['payload']['untung']),
                    ];
                }, $updates)
            );
        }

        if ($skipped) {
            $this->newLine();
            $this->warn('Snapshots matching the signature but left untouched: ' . count($skipped));
            $this->table(
                ['PC', 'Type', 'Row', 'Cost', 'Reason'],
                array_map(function ($s) {
                    return [$s['pc'], $s['model'], $s['id'], number_format($s['biaya']), $s['reason']];
                }, $skipped)
            );
        }
    }

    private function writeBackup(string $path, array $updates)
    {
        $directory = dirname($path);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $payload = array_map(function ($u) {
            return [
                'pending_change_id' => $u['pc'],
                'changeable_id' => $u['id'],
                'original_new_data' => json_decode($u['original_json'], true),
            ];
        }, $updates);

        file_put_contents($path, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
