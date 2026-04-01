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

class TransactionProcessExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithEvents
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Order ID',
            'Customer',
            'No. Telp',
            'Service',
            'Item Service',
            'Biaya',
            'Modal',
            'Total Biaya',
            'Payment Method',
            'Teknisi',
            'Warranty',
            'Status'
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
            $transaction->customer_name ?? '-',
            $transaction->customer_phone ?? '-',
            $transaction->service,
            $transaction->service_name ?? '-',
            number_format($transaction->first_item_biaya),
            number_format($transaction->first_item_modal),
            number_format($transaction->total_biaya),
            strtoupper($transaction->payment_method ?? '-'),
            $transaction->technician_name ?? '-',
            $transaction->warranty ? $transaction->warranty . ' ' . $transaction->warranty_type : '-',
            strtoupper($transaction->status)
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
                $sheet->getStyle('A1:N1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '2196F3']
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

                // Add summary section
                $lastRow = $sheet->getHighestRow();
                $summaryRow = $lastRow + 3;

                // Summary title
                $sheet->setCellValue('A' . $summaryRow, 'RINGKASAN');
                $sheet->mergeCells('A' . $summaryRow . ':C' . $summaryRow);
                $sheet->getStyle('A' . $summaryRow)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E0E0E0']
                    ]
                ]);

                // Summary data
                $detailRow = $summaryRow + 1;
                $sheet->setCellValue('A' . $detailRow, 'Total Transaksi:');
                $sheet->setCellValue('B' . $detailRow, $this->data->count());
                
                $detailRow++;
                $sheet->setCellValue('A' . $detailRow, 'Total Nilai:');
                $sheet->setCellValue('B' . $detailRow, 'Rp ' . number_format($this->data->sum('total_biaya')));
                
                $detailRow++;
                $sheet->setCellValue('A' . $detailRow, 'Status:');
                $sheet->setCellValue('B' . $detailRow, 'PROSES');

                // Style summary
                $sheet->getStyle('A' . ($summaryRow + 1) . ':B' . $detailRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN
                        ]
                    ]
                ]);

                // Auto adjust column widths
                foreach(range('A','N') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                // Add border to all data
                $sheet->getStyle('A1:N' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN
                        ]
                    ]
                ]);
            }
        ];
    }
}