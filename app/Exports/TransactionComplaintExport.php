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

class TransactionComplaintExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithEvents
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
                        'startColor' => ['rgb' => 'FF5722']
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
                $sheet->setCellValue('A' . $summaryRow, 'RINGKASAN COMPLAINT');
                $sheet->mergeCells('A' . $summaryRow . ':C' . $summaryRow);
                $sheet->getStyle('A' . $summaryRow)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFE0B2']
                    ]
                ]);

                // Summary data
                $detailRow = $summaryRow + 1;
                $sheet->setCellValue('A' . $detailRow, 'Total Transaksi Complaint:');
                $sheet->setCellValue('B' . $detailRow, $this->data->count());
                
                $detailRow++;
                $sheet->setCellValue('A' . $detailRow, 'Total Nilai:');
                $sheet->setCellValue('B' . $detailRow, 'Rp ' . number_format($this->data->sum('total_biaya')));
                
                $detailRow++;
                $sheet->setCellValue('A' . $detailRow, 'Status:');
                $sheet->setCellValue('B' . $detailRow, 'COMPLAINT');

                // Style summary
                $sheet->getStyle('A' . ($summaryRow + 1) . ':B' . $detailRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN
                        ]
                    ]
                ]);

                // Add notes section
                $notesRow = $detailRow + 2;
                $sheet->setCellValue('A' . $notesRow, 'CATATAN:');
                $sheet->getStyle('A' . $notesRow)->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FF0000']]
                ]);
                
                $notesRow++;
                $sheet->setCellValue('A' . $notesRow, '- Transaksi dengan status complaint memerlukan penanganan khusus');
                $notesRow++;
                $sheet->setCellValue('A' . $notesRow, '- Harap segera follow up dengan customer terkait');

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