<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Expenditure;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public $todayTransaction;
    public $todayIncome;
    public $todayExpenditure;

    public $dataChart;
    public $labelChart;

    public $dataTransaction;

    public function mount()
    {
        $now = Carbon::now()->format('Y-m-d');
        $transaction = Transaction::select(DB::raw("SUM(biaya) as biaya"), DB::raw("COUNT(id) as transaction"))
            ->whereDate('created_at', $now)->get();

        $this->todayTransaction = $transaction[0]['transaction'];
        $this->todayIncome = $transaction[0]['biaya'];

        $expenditur = Expenditure::select(DB::raw("SUM('total') as total"))
            ->whereDate('created_at', $now)
            ->get();

        $this->todayExpenditure = $expenditur[0]['total'];

        $transactionChart =
            DB::table('transactions as t1')
            ->selectRaw("GROUP_CONCAT(total_fee SEPARATOR ', ') as monthly_fees, GROUP_CONCAT(month SEPARATOR ', ') as month")
            ->fromSub(function ($query) {
                $query->from('transactions')
                    ->selectRaw("SUM(biaya) as total_fee, MONTHNAME(created_at) as month")
                    ->groupBy(DB::raw('MONTH(created_at)'), DB::raw('MONTHNAME(created_at)'));
            }, 't1')
            ->get();

        $this->dataChart = explode(',', $transactionChart[0]->monthly_fees);
        $this->labelChart = explode(',', $transactionChart[0]->month);

        $this->dataTransaction = Transaction::take(6)->latest()->get();
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard')
            ->layout('components.layouts.dashboard');
    }
}
