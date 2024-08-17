<?php

namespace App\Http\Livewire\Dashboard\Reporting;

use App\Exports\MonthlyReport;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Export extends Component
{
    public  $year, $month;
    public $selectItems = ['transaction', 'expenditure', 'income', 'technician', 'netto'];

    protected $rules = [
        'month' => 'required',
        'year' => 'required',
        'selectItems' => 'required'
    ];

    protected $messages = [
        'month.required' => 'The month field is required',
        'year.required' => 'The year field is required',
        'selectItems.required' => 'The items filed is required'
    ];

    public function render()
    {
        return view('livewire.dashboard.reporting.export');
    }

    public function handleExport()
    {
        $this->validate();
        $filename = 'DailyReport_' . $this->month . '_' . $this->year . '.xlsx';
        return Excel::download(new MonthlyReport($this->month, $this->year, $this->selectItems), $filename);
    }
}
