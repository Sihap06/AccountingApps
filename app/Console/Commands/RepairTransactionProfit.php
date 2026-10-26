<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Repairs rows whose modal/fee_teknisi/untung were written by the old update
 * bug, which applied the no-sparepart fee formula to rows that do have a
 * sparepart. Only rows carrying that exact signature are touched, and only
 * when the sparepart price at the time of the transaction can be recovered
 * from the pending_changes snapshot. Dry run unless --apply is passed.
 */
class RepairTransactionProfit extends Command
{
    protected $signature = 'transaction:repair-profit
                            {--apply : Write the corrected values to the database}
                            {--backup= : Path for the CSV backup of the old values}';

    protected $description = 'Recalculate modal/fee_teknisi/untung for rows corrupted by the sparepart fee bug';

    /**
     * Rows created before percent_with_sparepart existed were computed with a
     * formula that was correct at the time, so they are left alone.
     */
    private const CUTOFF_DATE = '2025-07-28';

    private const TARGETS = [
        'transactions' => \App\Models\Transaction::class,
        'transaction_items' => \App\Models\TransactionItem::class,
    ];

    public function handle()
    {
        $apply = (bool) $this->option('apply');

        $technicians = DB::table('technicians')->get()->keyBy('id');
        $products = DB::table('products')->get()->keyBy('id');

        $repairs = [];
        $skipped = [];

        foreach (self::TARGETS as $table => $model) {
            foreach (DB::table($table)->get() as $row) {
                if (!empty($row->deleted_at)) {
                    continue;
                }
                if ($row->product_id === null || !isset($products[$row->product_id])) {
                    continue;
                }
                if ($row->technical_id === null || !isset($technicians[$row->technical_id])) {
                    continue;
                }

                $technician = $technicians[$row->technical_id];
                $effectiveBiaya = (int) $row->biaya - (int) ($row->potongan ?? 0);

                // Signature of the bug: no-sparepart formula on a sparepart row.
                if ((int) $row->modal !== (int) $row->fee_teknisi) {
                    continue;
                }
                if ((int) round($effectiveBiaya * $technician->percent_fee / 100) !== (int) $row->fee_teknisi) {
                    continue;
                }

                $context = [
                    'table' => $table,
                    'id' => $row->id,
                    'date' => substr((string) $row->created_at, 0, 10),
                    'biaya' => $effectiveBiaya,
                ];

                if (substr((string) $row->created_at, 0, 10) < self::CUTOFF_DATE) {
                    $skipped[] = $context + ['reason' => 'predates percent_with_sparepart'];
                    continue;
                }

                $sparepartPrice = $this->recoverHistoricalSparepartPrice($model, $row->id, $row->product_id);
                if ($sparepartPrice === null) {
                    $skipped[] = $context + ['reason' => 'no usable historical price in pending_changes'];
                    continue;
                }

                if ($effectiveBiaya <= $sparepartPrice) {
                    $skipped[] = $context + ['reason' => 'cost <= sparepart price, would yield a negative fee'];
                    continue;
                }

                $fee = ($effectiveBiaya - $sparepartPrice) * $technician->percent_with_sparepart / 100;

                $repairs[] = [
                    'table' => $table,
                    'id' => $row->id,
                    'date' => $context['date'],
                    'biaya' => $effectiveBiaya,
                    'sparepart' => $sparepartPrice,
                    'old_modal' => (int) $row->modal,
                    'old_fee' => (int) $row->fee_teknisi,
                    'old_untung' => (int) $row->untung,
                    'new_modal' => (int) round($sparepartPrice + $fee),
                    'new_fee' => (int) round($fee),
                    'new_untung' => (int) round($effectiveBiaya - $sparepartPrice - $fee),
                ];
            }
        }

        $this->renderReport($repairs, $skipped);

        if (empty($repairs)) {
            $this->info('Nothing to repair.');
            return 0;
        }

        if (!$apply) {
            $this->newLine();
            $this->warn('DRY RUN - nothing was written. Re-run with --apply to commit these changes.');
            return 0;
        }

        $backupPath = $this->option('backup')
            ?: storage_path('app/repair-profit-backup-' . date('Ymd-His') . '.csv');
        $this->writeBackup($backupPath, $repairs);
        $this->info('Backup of the old values written to: ' . $backupPath);

        DB::transaction(function () use ($repairs) {
            foreach ($repairs as $repair) {
                DB::table($repair['table'])->where('id', $repair['id'])->update([
                    'modal' => $repair['new_modal'],
                    'fee_teknisi' => $repair['new_fee'],
                    'untung' => $repair['new_untung'],
                ]);
            }
        });

        $this->info('Applied ' . count($repairs) . ' repair(s).');

        return 0;
    }

    /**
     * The stored modal already bundles the technician fee, so the sparepart
     * price at the time is the difference between the two snapshot values.
     *
     * The snapshot is only usable when it refers to the same sparepart the row
     * carries now. An edit that swapped the sparepart would otherwise hand back
     * the price of the part that was replaced.
     */
    private function recoverHistoricalSparepartPrice(string $model, $id, $productId)
    {
        $snapshots = DB::table('pending_changes')
            ->where('changeable_type', $model)
            ->where('changeable_id', $id)
            ->whereNotNull('old_data')
            ->orderByDesc('id')
            ->get();

        // A row edited more than once has snapshots whose own old_data is
        // already corrupted; those yield a price of 0 and are stepped over in
        // favour of an older snapshot taken before the corruption.
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

    private function renderReport(array $repairs, array $skipped)
    {
        if ($repairs) {
            $this->info('Rows to repair: ' . count($repairs));
            $this->table(
                ['Table', 'ID', 'Date', 'Cost', 'Sparepart', 'Modal', 'Fee', 'Profit'],
                array_map(function ($r) {
                    return [
                        $r['table'],
                        $r['id'],
                        $r['date'],
                        number_format($r['biaya']),
                        number_format($r['sparepart']),
                        number_format($r['old_modal']) . ' -> ' . number_format($r['new_modal']),
                        number_format($r['old_fee']) . ' -> ' . number_format($r['new_fee']),
                        number_format($r['old_untung']) . ' -> ' . number_format($r['new_untung']),
                    ];
                }, $repairs)
            );

            $delta = 0;
            foreach ($repairs as $r) {
                $delta += $r['old_untung'] - $r['new_untung'];
            }
            $this->line('Net profit correction: ' . number_format($delta));
        }

        if ($skipped) {
            $this->newLine();
            $this->warn('Rows matching the signature but left untouched: ' . count($skipped));
            $this->table(
                ['Table', 'ID', 'Date', 'Cost', 'Reason'],
                array_map(function ($s) {
                    return [$s['table'], $s['id'], $s['date'], number_format($s['biaya']), $s['reason']];
                }, $skipped)
            );
        }
    }

    private function writeBackup(string $path, array $repairs)
    {
        $directory = dirname($path);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $handle = fopen($path, 'w');
        fputcsv($handle, ['table', 'id', 'old_modal', 'old_fee_teknisi', 'old_untung', 'new_modal', 'new_fee_teknisi', 'new_untung']);
        foreach ($repairs as $r) {
            fputcsv($handle, [
                $r['table'], $r['id'],
                $r['old_modal'], $r['old_fee'], $r['old_untung'],
                $r['new_modal'], $r['new_fee'], $r['new_untung'],
            ]);
        }
        fclose($handle);
    }
}
