<?php

namespace App\Http\Livewire\Dashboard\Reporting;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Expenditure;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FinancialSummaryExport;

class FinancialSummary extends Component
{
    public $selectedMonth;
    public $selectedYear;
    public $listMonth = [];
    public $listYear = [];

    // Summary data
    public $totalIncome = 0;
    public $totalExpenditure = 0;
    public $netto = 0;
    public $totalDone = 0;
    public $totalCancel = 0;


    public function mount()
    {
        // Check if user is master_admin
        if (auth()->user()->role !== 'master_admin') {
            abort(403, 'Unauthorized access');
        }
        // Initialize months
        $this->listMonth = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        ];

        // Initialize years (current year and 5 years back)
        $currentYear = date('Y');
        for ($i = 0; $i <= 5; $i++) {
            $this->listYear[$currentYear - $i] = $currentYear - $i;
        }

        // Set default to current month and year
        $this->selectedMonth = date('n');
        $this->selectedYear = date('Y');

        $this->calculateSummary();
    }

    public function updatedSelectedMonth()
    {
        $this->calculateSummary();
    }

    public function updatedSelectedYear()
    {
        $this->calculateSummary();
    }

    public function calculateSummary()
    {
        // Build date range for the selected month
        $startDate = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->startOfMonth();
        $endDate = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->endOfMonth();

        // Calculate Income (from completed transactions)
        // First get the count
        $this->totalDone = Transaction::where('status', 'done')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNull('deleted_at')
            ->count();

        // Calculate total income using subquery for transaction items
        $incomeData = DB::table('transactions as t')
            ->leftJoinSub(
                DB::table('transaction_items')
                    ->selectRaw('transaction_id, SUM(biaya) as total_item_biaya')
                    ->whereNull('deleted_at')
                    ->groupBy('transaction_id'),
                'ti',
                'ti.transaction_id',
                't.id'
            )
            ->where('t.status', 'done')
            ->whereBetween('t.created_at', [$startDate, $endDate])
            ->whereNull('t.deleted_at')
            ->selectRaw('SUM(t.biaya + IFNULL(ti.total_item_biaya, 0) - IFNULL(t.potongan, 0)) as total_income')
            ->first();

        $this->totalIncome = $incomeData->total_income ?? 0;

        // Calculate Expenditure
        $this->totalExpenditure = Expenditure::whereBetween('tanggal', [$startDate, $endDate])
            ->sum('total');

        // Calculate Cancelled transactions
        $this->totalCancel = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'cancel')
            ->whereNull('deleted_at')
            ->count();

        // Calculate Netto (Income - Expenditure)
        $this->netto = $this->totalIncome - $this->totalExpenditure;
    }

    public function exportToExcel()
    {
        $data = [
            ['Total Income', 'Rp ' . number_format($this->totalIncome)],
            ['Total Expenditure', 'Rp ' . number_format($this->totalExpenditure)],
            ['Net Profit', 'Rp ' . number_format($this->netto)],
            ['Completed Transactions', number_format($this->totalDone)],
            ['Cancelled Transactions', number_format($this->totalCancel)],
        ];

        $period = $this->listMonth[$this->selectedMonth] . ' ' . $this->selectedYear;
        $filename = 'financial_summary_' . $this->selectedYear . '_' . str_pad($this->selectedMonth, 2, '0', STR_PAD_LEFT) . '.xlsx';
        
        $export = new FinancialSummaryExport($data, $period);
        
        return Excel::download($export, $filename);
    }

    public function render()
    {
        return view('livewire.dashboard.reporting.financial-summary')
            ->layout('components.layouts.dashboard');
    }
}
