<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Contracts\View\View;
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

    public function view(): View
    {
        $data = Transaction::leftJoin('technicians', 'technicians.id', '=', 'transactions.technical_id')
            ->select('transactions.created_at', 'technicians.kode', 'transactions.service', 'transactions.biaya', 'transactions.modal', 'transactions.untung')
            ->whereMonth('transactions.created_at', $this->month)
            ->whereYear('transactions.created_at', $this->year)
            ->get();

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
