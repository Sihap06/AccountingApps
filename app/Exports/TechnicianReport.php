<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Contracts\View\View;
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

    public function view(): View
    {
        $data =
            Transaction::join('technicians', 'technicians.id', '=', 'transactions.technical_id')
            ->select('transactions.service', 'transactions.fee_teknisi', 'transactions.created_at', 'technicians.name')
            ->whereMonth('transactions.created_at', $this->month)
            ->whereYear('transactions.created_at', $this->year)
            ->orderBy('technicians.id', 'asc')
            ->get();

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
