<?php

namespace App\Exports;

use App\Models\Expenditure;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ExpenditureReport implements FromView, ShouldAutoSize, WithColumnFormatting, WithTitle
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
        $data = Expenditure::whereMonth('created_at', $this->month)
            ->whereYear('created_at', $this->year)
            ->get();

        return view('export.expenditure', [
            'data' => $data
        ]);
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
        ];
    }

    public function title(): string
    {
        return 'Expenditure';
    }
}
