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

class TechnicianReport implements FromView, ShouldAutoSize, WithColumnFormatting, WithTitle
{
    private $month;
    private $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    protected function filterOnlyTransactionTechnician(array $data)
    {
        $filtered = array_filter($data, function ($item) {
            return $item['technical_id'] !== null;
        });

        // Reindex the array to remove any gaps in keys
        return array_values($filtered);
    }

    public function view(): View
    {
        $data = [];

        $queryTeknisiTransaction = Transaction::leftJoin('technicians', 'technicians.id', '=', 'transactions.technical_id')
            ->select('transactions.id', 'transactions.service', 'transactions.fee_teknisi', 'transactions.created_at', 'technicians.name', 'transactions.technical_id')
            ->whereMonth('transactions.created_at', $this->month)
            ->whereYear('transactions.created_at', $this->year)
            ->where('status', 'done');

        $dataFeeTechnicianTransaction = $queryTeknisiTransaction->get()->toArray();
        $dataOnlyTransactionTechnician = $this->filterOnlyTransactionTechnician($dataFeeTechnicianTransaction);
        $data = [...$dataOnlyTransactionTechnician];

        foreach ($dataFeeTechnicianTransaction as $key => $value) {
            $queryTeknisiTransactionItem = TransactionItem::join('technicians', 'technicians.id', '=', 'transaction_items.technical_id')
                ->select('transaction_items.id', 'transaction_items.service', 'transaction_items.fee_teknisi', 'transaction_items.created_at', 'technicians.name')
                ->where('transaction_id', $value['id'])
                ->whereMonth('transaction_items.created_at', $this->month)
                ->whereYear('transaction_items.created_at', $this->year);

            $dataFeeTechnicianTransactionItem = $queryTeknisiTransactionItem->get()->toArray();
            $data = [...$data, ...$dataFeeTechnicianTransactionItem];
        }

        usort($data, function ($a, $b) {
            return strtotime($a['created_at']) - strtotime($b['created_at']);
        });


        return view('export.technician', [
            'data' => $data
        ]);
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function title(): string
    {
        return 'Technician';
    }
}
