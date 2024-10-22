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
        $transaction = Transaction::select(DB::raw("SUM(untung) as biaya"), DB::raw("COUNT(id) as transaction"))
            ->whereDate('created_at', $now)->get();

        $data = Transaction::leftJoin('transaction_items', function ($join) {
            $join->on('transactions.id', '=', 'transaction_items.transaction_id')
                ->whereNull('transaction_items.deleted_at');
        })
            ->select(
                DB::raw('transactions.biaya + IFNULL(SUM(transaction_items.biaya), 0) as total_biaya'),
            )
            ->where('transactions.status', 'done')
            ->whereNull('transactions.deleted_at')
            ->whereDate('transactions.created_at', $now)
            ->groupBy(
                'transactions.created_at',
                'transactions.id',
                'transactions.order_transaction',
                'transactions.biaya',
                'transactions.modal',
                'transactions.payment_method'
            );

        // Mengambil hasil query
        $data = $data->get();

        $todayTransaction = $data->count();
        $todayIncome = $data->sum('total_biaya');

        $expenditur = Expenditure::select(DB::raw("SUM(total) as total"))
            ->whereDate('tanggal', $now)
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

        $subQuery = DB::table('transactions')
            ->leftJoin('transaction_items', function ($join) {
                $join->on('transactions.id', '=', 'transaction_items.transaction_id')
                    ->whereNull('transaction_items.deleted_at'); // Only join valid transaction items
            })
            ->leftJoin('customers', 'transactions.customer_id', '=', 'customers.id')
            ->select(
                'transactions.id',
                'transactions.status',
            )
            // ->whereBetween('transactions.created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->whereNull('transactions.deleted_at')
            ->groupBy(
                'transactions.id',
                'transactions.status',
                'transactions.biaya'
            );

        $transactionsMonthly = DB::table(DB::raw("({$subQuery->toSql()}) as sub"))
            ->select(
                DB::raw('SUM(CASE WHEN sub.status = "done" THEN 1 ELSE 0 END) as total_transaksi_done'),
                DB::raw('SUM(CASE WHEN sub.status = "cancel" THEN 1 ELSE 0 END) as total_transaksi_cancel'),
                DB::raw('SUM(CASE WHEN sub.status = "proses" THEN 1 ELSE 0 END) as total_transaksi_proses'),
            )
            ->mergeBindings($subQuery)
            ->get();


        return view('livewire.dashboard.dashboard', compact('dataChart', 'labelChart', 'todayTransaction', 'todayIncome', 'todayExpenditure', 'dataTransaction', 'transactionsMonthly'))
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
