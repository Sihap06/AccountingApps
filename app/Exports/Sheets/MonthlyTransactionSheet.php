<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
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

class MonthlyTransactionSheet implements FromCollection, WithTitle, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithEvents
{
    protected $transactions;
    protected $monthName;
    protected $year;
    private $rowNumber = 0;

    public function __construct($transactions, $monthName, $year)
    {
        $this->transactions = $transactions;
        $this->monthName = $monthName;
        $this->year = $year;
    }

    public function collection()
    {
        return $this->transactions;
    }

    public function title(): string
    {
        return $this->monthName;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'No. Order',
            'Layanan',
            'Customer ID',
            'Biaya',
            'Total Fee',
            'Status',
            'Metode Pembayaran'
        ];
    }

    public function map($transaction): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            date('d-m-Y H:i:s', strtotime($transaction->created_at)),
            $transaction->order_transaction,
            $transaction->service,
            $transaction->customer_id ?? '-',
            'Rp ' . number_format($transaction->biaya, 0, ',', '.'),
            'Rp ' . number_format($transaction->total_fee, 0, ',', '.'),
            strtoupper($transaction->status),
            strtoupper($transaction->payment_method)
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
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();
                
                // Style header
                $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
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
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN
                        ]
                    ]
                ]);
                
                // Add borders to all data cells
                $sheet->getStyle('A2:' . $lastColumn . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN
                        ]
                    ]
                ]);
                
                // Add summary section
                $summaryRow = $lastRow + 2;
                
                // Summary title
                $sheet->setCellValue('A' . $summaryRow, 'RINGKASAN BULAN ' . strtoupper($this->monthName) . ' ' . $this->year);
                $sheet->mergeCells('A' . $summaryRow . ':C' . $summaryRow);
                $sheet->getStyle('A' . $summaryRow)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
                ]);
                
                // Summary data
                $dataRow = $summaryRow + 2;
                $totalTransactions = $this->transactions->count();
                $totalRevenue = $this->transactions->sum('total_fee');
                $totalBiaya = $this->transactions->sum('biaya');
                
                $sheet->setCellValue('A' . $dataRow, 'Total Transaksi:');
                $sheet->setCellValue('B' . $dataRow, $totalTransactions . ' transaksi');
                
                $dataRow++;
                $sheet->setCellValue('A' . $dataRow, 'Total Biaya:');
                $sheet->setCellValue('B' . $dataRow, 'Rp ' . number_format($totalBiaya, 0, ',', '.'));
                
                $dataRow++;
                $sheet->setCellValue('A' . $dataRow, 'Total Pendapatan:');
                $sheet->setCellValue('B' . $dataRow, 'Rp ' . number_format($totalRevenue, 0, ',', '.'));
                
                $dataRow++;
                $sheet->setCellValue('A' . $dataRow, 'Rata-rata per Transaksi:');
                $sheet->setCellValue('B' . $dataRow, 'Rp ' . number_format($totalRevenue / $totalTransactions, 0, ',', '.'));
                
                // Style summary section
                $sheet->getStyle('A' . ($summaryRow + 2) . ':B' . $dataRow)->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_MEDIUM
                        ]
                    ]
                ]);
                
                // Payment method breakdown
                $methodRow = $dataRow + 2;
                $sheet->setCellValue('A' . $methodRow, 'BREAKDOWN METODE PEMBAYARAN');
                $sheet->mergeCells('A' . $methodRow . ':C' . $methodRow);
                $sheet->getStyle('A' . $methodRow)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E0E0E0']
                    ]
                ]);
                
                $methodRow++;
                $paymentMethods = $this->transactions->groupBy('payment_method');
                foreach ($paymentMethods as $method => $transactions) {
                    $sheet->setCellValue('A' . $methodRow, strtoupper($method) . ':');
                    $sheet->setCellValue('B' . $methodRow, $transactions->count() . ' transaksi');
                    $sheet->setCellValue('C' . $methodRow, 'Rp ' . number_format($transactions->sum('total_fee'), 0, ',', '.'));
                    $methodRow++;
                }
                
                // Auto adjust column widths
                foreach(range('A', $lastColumn) as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            }
        ];
    }
}