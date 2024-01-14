<?php

namespace App\Http\Livewire\Dashboard\Reporting;

use App\Models\Expenditure;
use App\Models\Technician;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Income extends Component
{
    public $selectTechnician;
    public $selectedYear;
    public $selectedMonth;

    public function mount()
    {
        $this->selectedYear = Carbon::now()->format('Y');
        $this->selectedMonth = Carbon::now()->format('m');
    }

    public function render()
    {
        $dataFeeTechnician = [];
        $totalFeeTeknisi = 0;
        if ($this->selectTechnician !== null) {
            $queryTeknisi = Transaction::select('service', 'fee_teknisi', 'order_transaction', 'created_at')
                ->where('technical_id', $this->selectTechnician)
                ->whereMonth('created_at', $this->selectedMonth)
                ->whereYear('created_at', $this->selectedYear);

            $dataFeeTechnician = $queryTeknisi->get();
            $totalFeeTeknisi = $queryTeknisi->sum('fee_teknisi');
        }


        $technician = Technician::all();

        $income = Transaction::select(DB::raw("SUM(untung) as total"), DB::raw('Date(created_at) as tanggal'))
            ->whereMonth('created_at', $this->selectedMonth)
            ->whereYear('created_at', $this->selectedYear)
            ->groupBy(DB::raw('Date(created_at)'))
            ->get();

        $totalIncome = Transaction::whereMonth('created_at', $this->selectedMonth)
            ->whereYear('created_at', $this->selectedYear)
            ->sum('untung');

        $totalExpenditure = Expenditure::whereMonth('created_at', $this->selectedMonth)
            ->whereYear('created_at', $this->selectedYear)
            ->sum('total');

        $totalNetto = $totalIncome - $totalExpenditure;

        $listYear = ['2023', '2024', '2025', '2026', '2027'];
        $listMonth = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];


        return view('livewire.dashboard.reporting.income', compact('technician', 'dataFeeTechnician', 'income', 'listYear', 'listMonth', 'totalFeeTeknisi', 'totalIncome', 'totalExpenditure', 'totalNetto'));
    }
}
