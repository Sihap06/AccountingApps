<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Expenditure;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public $selectedYear;

    public function mount()
    {
        $this->selectedYear = Carbon::now()->format('Y');
    }

    public function render()
    {
        $now = Carbon::now()->format('Y-m-d');
        $transaction = Transaction::select(DB::raw("SUM(biaya) as biaya"), DB::raw("COUNT(id) as transaction"))
            ->whereDate('created_at', $now)->get();

        $todayTransaction = $transaction[0]['transaction'];
        $todayIncome = $transaction[0]['biaya'];

        $expenditur = Expenditure::select(DB::raw("SUM(total) as total"))
            ->whereDate('created_at', $now)
            ->get();

        $todayExpenditure = $expenditur[0]['total'];

        $transactionChart =
            DB::table('transactions as t1')
            ->selectRaw("GROUP_CONCAT(total_fee SEPARATOR ', ') as monthly_fees, GROUP_CONCAT(month SEPARATOR ', ') as month")
            ->fromSub(function ($query) {
                $query->from('transactions')
                    ->selectRaw("SUM(biaya) as total_fee, MONTHNAME(created_at) as month")
                    ->whereYear('created_at', $this->selectedYear)
                    ->groupBy(DB::raw('MONTH(created_at)'), DB::raw('MONTHNAME(created_at)'));
            }, 't1')
            ->get();

        $dataChart = explode(',', $transactionChart[0]->monthly_fees);
        $labelChart = explode(',', $transactionChart[0]->month);

        $dataTransaction = Transaction::take(6)->latest()->get();

        return view('livewire.dashboard.dashboard', compact('dataChart', 'labelChart', 'todayTransaction', 'todayIncome', 'todayExpenditure', 'dataTransaction'))
            ->layout('components.layouts.dashboard');
    }

    public function updateChart()
    {
        $transactionChart =
            DB::table('transactions as t1')
            ->selectRaw("GROUP_CONCAT(total_fee SEPARATOR ', ') as monthly_fees, GROUP_CONCAT(month SEPARATOR ', ') as month")
            ->fromSub(function ($query) {
                $query->from('transactions')
                    ->selectRaw("SUM(biaya) as total_fee, MONTHNAME(created_at) as month")
                    ->whereYear('created_at', $this->selectedYear)
                    ->groupBy(DB::raw('MONTH(created_at)'), DB::raw('MONTHNAME(created_at)'));
            }, 't1')
            ->get();

        $dataChart = explode(',', $transactionChart[0]->monthly_fees);
        $labelChart = explode(',', $transactionChart[0]->month);

        $this->emit('chartUpdate', $dataChart, $labelChart);
    }
}
