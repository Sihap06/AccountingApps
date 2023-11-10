<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public $todayTransaction;
    public $todayIncome;
    public $todayExpenditure;

    public function mount()
    {
        $now = Carbon::now()->format('Y-m-d');
        $transaction = Transaction::select(DB::raw("SUM(untung) as income"), DB::raw("COUNT(id) as transaction"))
            ->whereDate('created_at', $now)->get();

        $this->todayTransaction = $transaction[0]['transaction'];
        $this->todayIncome = $transaction[0]['income'];
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard')
            ->layout('components.layouts.dashboard');
    }
}
