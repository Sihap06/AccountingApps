<?php

namespace App\Exports;

use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TransactionReport implements FromView, ShouldAutoSize, WithColumnFormatting, WithTitle
{
    private $month;
    private $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    function compareByCreatedAt($a, $b)
    {
        return strtotime($a['created_at']) - strtotime($b['created_at']);
    }

    public function view(): View
    {
        $data = [];
        $queryDataTransaction = Transaction::leftJoin('technicians', 'technicians.id', '=', 'transactions.technical_id')
            ->select('transactions.created_at', 'technicians.kode', 'transactions.service', 'transactions.biaya', 'transactions.modal', 'transactions.untung', 'transactions.status', 'transactions.id')
            ->whereMonth('transactions.created_at', $this->month)
            ->whereYear('transactions.created_at', $this->year)
            ->whereNull('transactions.deleted_at')
            ->where('status', 'done');

        $dataTransaction = $queryDataTransaction->get()->toArray();
        $data = [...$dataTransaction];

        foreach ($dataTransaction as $key => $value) {
            $queryDataTransactionItem = TransactionItem::leftJoin('technicians', 'technicians.id', '=', 'transaction_items.technical_id')
                ->select('transaction_items.created_at', 'technicians.kode', 'transaction_items.service', 'transaction_items.biaya', 'transaction_items.modal', 'transaction_items.untung')
                ->whereNull('transaction_items.deleted_at')
                ->where('transaction_id', $value['id']);

            $dataTransactionItemDone = $queryDataTransactionItem->get()->toArray();
            $data = [...$data, ...$dataTransactionItemDone];
        }

        usort($data, function ($a, $b) {
            return strtotime($a['created_at']) - strtotime($b['created_at']);
        });

        return view('export.transaction', [
            'data' => $data
        ]);
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function title(): string
    {
        return 'Transaction';
    }
}
