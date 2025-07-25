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
use Carbon\Carbon;

class CustomersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithEvents
{
    protected $customers;
    protected $startDate;
    protected $endDate;

    public function __construct($customers, $startDate = null, $endDate = null)
    {
        $this->customers = $customers;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return $this->customers;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'No. HP'
        ];
    }

    public function map($customer): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $customer->name,
            $customer->no_telp
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
                $sheet->getStyle('A1:C1')->applyFromArray([
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

                // Add filter info at the top
                if ($this->startDate || $this->endDate) {
                    $sheet->insertNewRowBefore(1, 3);
                    
                    $sheet->setCellValue('A1', 'DATA CUSTOMER');
                    $sheet->mergeCells('A1:C1');
                    $sheet->getStyle('A1')->applyFromArray([
                        'font' => ['bold' => true, 'size' => 16],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                    ]);

                    $filterText = 'Filter Tanggal: ';
                    if ($this->startDate && $this->endDate) {
                        $filterText .= $this->startDate->format('d-m-Y') . ' s/d ' . $this->endDate->format('d-m-Y');
                    } elseif ($this->startDate) {
                        $filterText .= 'Dari ' . $this->startDate->format('d-m-Y');
                    } elseif ($this->endDate) {
                        $filterText .= 'Sampai ' . $this->endDate->format('d-m-Y');
                    }

                    $sheet->setCellValue('A2', $filterText);
                    $sheet->mergeCells('A2:C2');
                    $sheet->getStyle('A2')->applyFromArray([
                        'font' => ['italic' => true],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                    ]);

                    // Update header row position
                    $headerRow = 4;
                } else {
                    $headerRow = 1;
                }

                // Get last row with data
                $lastRow = $sheet->getHighestRow();
                
                // Add summary section
                $summaryRow = $lastRow + 3;
                
                // Summary title
                $sheet->setCellValue('A' . $summaryRow, 'TOTAL DATA');
                $sheet->mergeCells('A' . $summaryRow . ':B' . $summaryRow);
                $sheet->getStyle('A' . $summaryRow)->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E0E0E0']
                    ]
                ]);

                $sheet->setCellValue('C' . $summaryRow, $this->customers->count() . ' Customer');
                $sheet->getStyle('C' . $summaryRow)->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
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

                // Add export date
                $exportRow = $summaryRow + 2;
                $sheet->setCellValue('A' . $exportRow, 'Diekspor pada: ' . Carbon::now()->format('d-m-Y H:i:s'));
                $sheet->getStyle('A' . $exportRow)->applyFromArray([
                    'font' => ['italic' => true, 'size' => 10]
                ]);

                // Auto adjust column widths
                foreach(range('A','C') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                // Add border to all data
                $dataEndRow = $this->startDate || $this->endDate ? $lastRow : $lastRow;
                $sheet->getStyle('A' . $headerRow . ':C' . $dataEndRow)->applyFromArray([
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