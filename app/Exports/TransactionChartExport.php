<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class TransactionChartExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithEvents
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

    public function collection()
    {
        return $this->transactionData;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Order Transaction',
            'Service',
            'Customer ID',
            'Biaya',
            'Total Fee',
            'Status',
            'Payment Method',
            'Bulan'
        ];
    }

    public function map($transaction): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            date('d-m-Y H:i:s', strtotime($transaction->created_at)),
            $transaction->order_transaction,
            $transaction->service,
            $transaction->customer_id ?? '-',
            number_format($transaction->biaya),
            number_format($transaction->total_fee),
            strtoupper($transaction->status),
            strtoupper($transaction->payment_method),
            $transaction->month
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                
                // Style header
                $sheet->getStyle('A1:J1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4CAF50']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                // Add summary section
                $lastRow = $sheet->getHighestRow();
                $summaryStartRow = $lastRow + 3;

                // Summary title
                $sheet->setCellValue('A' . $summaryStartRow, 'RINGKASAN CHART TRANSAKSI ' . $this->selectedYear);
                $sheet->mergeCells('A' . $summaryStartRow . ':D' . $summaryStartRow);
                $sheet->getStyle('A' . $summaryStartRow)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                // Monthly summary
                $monthRow = $summaryStartRow + 2;
                $sheet->setCellValue('A' . $monthRow, 'Bulan');
                $sheet->setCellValue('B' . $monthRow, 'Total Transaksi (Rp)');
                
                $sheet->getStyle('A' . $monthRow . ':B' . $monthRow)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E0E0E0']
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN
                        ]
                    ]
                ]);

                // Add chart data
                $dataRow = $monthRow + 1;
                foreach ($this->chartLabels as $index => $label) {
                    $sheet->setCellValue('A' . $dataRow, $label);
                    $sheet->setCellValue('B' . $dataRow, isset($this->chartData[$index]) ? number_format($this->chartData[$index]) : '0');
                    
                    $sheet->getStyle('A' . $dataRow . ':B' . $dataRow)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN
                            ]
                        ]
                    ]);
                    
                    $dataRow++;
                }

                // Total row
                $sheet->setCellValue('A' . $dataRow, 'TOTAL');
                $sheet->setCellValue('B' . $dataRow, number_format(array_sum($this->chartData)));
                $sheet->getStyle('A' . $dataRow . ':B' . $dataRow)->applyFromArray([
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

                // Auto adjust column widths
                foreach(range('A','J') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            }
        ];
    }
}