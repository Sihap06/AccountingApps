<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MonthlyReport implements WithMultipleSheets
{
    use Exportable;
    protected $month;
    protected $year;
    protected $selectItems;

    public function __construct($month, $year, $selectItems)
    {
        $this->month = $month;
        $this->year = $year;
        $this->selectItems = $selectItems;
    }

    public function sheets(): array
    {

        $sheets = [];

        foreach ($this->selectItems as $value) {
            if ($value === 'transaction') {
                $sheets[] = new TransactionReport($this->month, $this->year);
            } elseif ($value === 'expenditure') {
                $sheets[] = new ExpenditureReport($this->month, $this->year);
            } elseif ($value === 'income') {
                $sheets[] = new IncomeReport($this->month, $this->year);
            } elseif ($value === 'technician') {
                $sheets[] = new TechnicianReport($this->month, $this->year);
            } elseif ($value === 'netto') {
                $sheets[] = new NettoExport($this->month, $this->year);
            }
        }

        return $sheets;
    }
}
