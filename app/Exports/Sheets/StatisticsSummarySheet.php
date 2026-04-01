<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

class StatisticsSummarySheet implements FromArray, WithTitle, WithStyles, ShouldAutoSize, WithEvents
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

    public function array(): array
    {
        $data = [];
        
        // Title
        $data[] = ['STATISTIK TRANSAKSI TAHUN ' . $this->selectedYear];
        $data[] = [''];
        
        // Summary statistics
        $data[] = ['RINGKASAN STATISTIK'];
        $data[] = [''];
        
        $totalTransactions = $this->transactionData->count();
        $totalRevenue = $this->transactionData->sum('total_fee');
        $avgRevenue = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;
        
        $data[] = ['Total Transaksi:', $totalTransactions . ' transaksi'];
        $data[] = ['Total Pendapatan:', 'Rp ' . number_format($totalRevenue, 0, ',', '.')];
        $data[] = ['Rata-rata per Transaksi:', 'Rp ' . number_format($avgRevenue, 0, ',', '.')];
        $data[] = [''];
        
        // Monthly summary
        $data[] = ['RINGKASAN BULANAN'];
        $data[] = [''];
        $data[] = ['Bulan', 'Jumlah Transaksi', 'Total Pendapatan (Rp)', 'Rata-rata (Rp)'];
        
        // Group by month for statistics
        $monthlyStats = $this->transactionData->groupBy('month_number');
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        foreach ($monthNames as $monthNum => $monthName) {
            if (isset($this->chartLabels[array_search($monthName, $this->chartLabels, true)])) {
                $index = array_search($monthName, $this->chartLabels, true);
                $monthData = $monthlyStats->get($monthNum, collect());
                $count = $monthData->count();
                $total = isset($this->chartData[$index]) ? $this->chartData[$index] : 0;
                $avg = $count > 0 ? $total / $count : 0;
                
                $data[] = [
                    $monthName,
                    $count,
                    number_format($total, 0, ',', '.'),
                    number_format($avg, 0, ',', '.')
                ];
            }
        }
        
        // Total row
        $data[] = [
            'TOTAL',
            $totalTransactions,
            number_format($totalRevenue, 0, ',', '.'),
            number_format($avgRevenue, 0, ',', '.')
        ];
        
        $data[] = [''];
        $data[] = [''];
        
        // Payment method summary
        $data[] = ['RINGKASAN METODE PEMBAYARAN'];
        $data[] = [''];
        $data[] = ['Metode Pembayaran', 'Jumlah Transaksi', 'Total (Rp)', 'Persentase'];
        
        $paymentMethods = $this->transactionData->groupBy('payment_method');
        foreach ($paymentMethods as $method => $transactions) {
            $count = $transactions->count();
            $total = $transactions->sum('total_fee');
            $percentage = ($total / $totalRevenue) * 100;
            
            $data[] = [
                strtoupper($method),
                $count,
                number_format($total, 0, ',', '.'),
                number_format($percentage, 2, ',', '.') . '%'
            ];
        }
        
        return $data;
    }

    public function title(): string
    {
        return 'Statistik';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 16],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            3 => ['font' => ['bold' => true, 'size' => 14]],
            10 => ['font' => ['bold' => true, 'size' => 14]],
            12 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                
                // Merge title cell
                $sheet->mergeCells('A1:D1');
                
                // Style headers
                $this->styleHeaderRow($sheet, 12);
                
                // Find and style the total row
                $highestRow = $sheet->getHighestRow();
                for ($row = 13; $row <= $highestRow; $row++) {
                    if ($sheet->getCell('A' . $row)->getValue() == 'TOTAL') {
                        $sheet->getStyle('A' . $row . ':D' . $row)->applyFromArray([
                            'font' => ['bold' => true],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'FFFF00']
                            ],
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => Border::BORDER_THIN
                                ]
                            ]
                        ]);
                        break;
                    }
                }
                
                // Style payment method header
                for ($row = 1; $row <= $highestRow; $row++) {
                    if ($sheet->getCell('A' . $row)->getValue() == 'Metode Pembayaran') {
                        $this->styleHeaderRow($sheet, $row);
                        break;
                    }
                }
                
                // Auto adjust column widths
                foreach(range('A','D') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            }
        ];
    }
    
    private function styleHeaderRow($sheet, $row)
    {
        $sheet->getStyle('A' . $row . ':D' . $row)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4CAF50']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ]);
    }
}