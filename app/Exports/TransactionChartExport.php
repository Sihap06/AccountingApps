<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TransactionChartExport implements WithMultipleSheets
{
    protected $transactionData;
    protected $chartData;
    protected $chartLabels;
    protected $selectedYear;

    public function __construct($transactionData, $chartData, $chartLabels, $selectedYear)
    {
        $this->transactionData = $transactionData;
        $this->chartData = $chartData;
        $this->chartLabels = $chartLabels;
        $this->selectedYear = $selectedYear;
    }

    public function sheets(): array
    {
        $sheets = [];
        
        // First sheet: Statistics Summary
        $sheets[] = new Sheets\StatisticsSummarySheet(
            $this->transactionData,
            $this->chartData,
            $this->chartLabels,
            $this->selectedYear
        );
        
        // Group transactions by month
        $transactionsByMonth = $this->transactionData->groupBy('month_number');
        
        // Create a sheet for each month
        $monthNames = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        
        foreach ($monthNames as $monthNumber => $monthName) {
            if ($transactionsByMonth->has($monthNumber)) {
                $sheets[] = new Sheets\MonthlyTransactionSheet(
                    $transactionsByMonth->get($monthNumber),
                    $monthName,
                    $this->selectedYear
                );
            }
        }
        
        return $sheets;
    }
}