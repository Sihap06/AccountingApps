<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class IncomeReport implements FromView, ShouldAutoSize, WithColumnFormatting, WithTitle
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
        $data = Transaction::select(DB::raw("SUM(untung) as total"), DB::raw('Date(created_at) as tanggal'))
            ->whereMonth('created_at', $this->month)
            ->whereYear('created_at', $this->year)
            ->groupBy(DB::raw('Date(created_at)'))
            ->get();

        return view('export.income', [
            'data' => $data
        ]);
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function title(): string
    {
        return 'Income';
    }
}
