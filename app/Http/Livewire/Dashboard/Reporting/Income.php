<?php

namespace App\Http\Livewire\Dashboard\Reporting;

use App\Models\Technician;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Income extends Component
{
    public $selectTechnician;

    public function render()
    {
        $dataFeeTechnician = [];
        if ($this->selectTechnician !== null) {
            $dataFeeTechnician = Transaction::select('service', 'fee_teknisi')
                ->where('technical_id', $this->selectTechnician)->get();
        }
        $technician = Technician::all();

        $income = Transaction::select(DB::raw("SUM(untung) as total"), DB::raw('Date(created_at) as tanggal'))
            ->groupBy(DB::raw('Date(created_at)'))
            ->get();


        return view('livewire.dashboard.reporting.income', compact('technician', 'dataFeeTechnician', 'income'));
    }
}
