<?php

namespace App\Http\Livewire\Dashboard\Reporting;

use App\Models\Expenditure;
use App\Models\Technician;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Income extends Component
{
    public $selectTechnician;
    public $selectedYear;
    public $selectedMonth;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->selectedYear = Carbon::now()->format('Y');
        $this->selectedMonth = Carbon::now()->format('m');
    }

    public function getIncome()
    {
        $queryTransaction = Transaction::select('id', 'service', 'untung', 'created_at', 'order_transaction')
            ->whereMonth('created_at', $this->selectedMonth)
            ->whereYear('created_at', $this->selectedYear)
            ->where('status', 'done');

        $dataIncomeTransaction = $queryTransaction->get()->toArray();
        $dataIncome = [...$dataIncomeTransaction];

        foreach ($dataIncomeTransaction as $key => $value) {
            $queryTransactionItem = TransactionItem::select('service', 'untung', 'created_at')
                ->addSelect(DB::raw("'" . $value['order_transaction'] . "' as order_transaction"))
                ->addSelect(DB::raw("'" . $value['id'] . "' as id"))
                ->where('transaction_id', $value['id'])
                ->whereMonth('created_at', $this->selectedMonth)
                ->whereYear('created_at', $this->selectedYear);

            $dataIncomeTransactionItem = $queryTransactionItem->get()->toArray();
            $dataIncome = [...$dataIncome, ...$dataIncomeTransactionItem];
        }

        $collection = collect($dataIncome);
        $income = $collection->groupBy(function ($item) {
            return Carbon::parse($item['created_at'])->toDateString(); // Group by date part only
        })->map(function ($group) {
            return [
                'tanggal' => $group->first()['created_at'],
                'total' => $group->sum('untung')
            ];
        })->sortBy(function ($item) {
            return Carbon::parse($item['tanggal']); // Sort by 'tanggal'
        });

        $totalIncome = $collection->sum('untung');

        return [
            'income' => $income,
            'total_income' => $totalIncome,
        ];
    }

    protected function filterByColumnValue(array $data)
    {
        $filtered = array_filter($data, function ($item) {
            return $item['technical_id'] === (int)$this->selectTechnician;
        });

        // Reindex the array to remove any gaps in keys
        return array_values($filtered);
    }

    public function getTechnician()
    {
        $dataFeeTechnician = [];
        $totalFeeTeknisi = 0;

        if ($this->selectTechnician === '') {
            $this->selectTechnician = null;
        }

        if ($this->selectTechnician !== null) {
            $queryTeknisiTransaction = Transaction::select('id', 'service', 'fee_teknisi', 'created_at', 'order_transaction', 'technical_id')
                ->whereMonth('created_at', $this->selectedMonth)
                ->whereYear('created_at', $this->selectedYear)
                ->where('status', 'done');

            $dataFeeTechnicianTransaction = $queryTeknisiTransaction->get()->toArray();
            $dataFeeSelectedTechnicain = $this->filterByColumnValue($dataFeeTechnicianTransaction, 'technical_id', $this->selectTechnician);
            $dataFeeTechnician = [...$dataFeeSelectedTechnicain];

            foreach ($dataFeeTechnicianTransaction as $key => $value) {
                $queryTeknisiTransactionItem = TransactionItem::select('service', 'fee_teknisi', 'created_at')
                    ->addSelect(DB::raw("'" . $value['order_transaction'] . "' as order_transaction"))
                    ->addSelect(DB::raw("'" . $value['id'] . "' as id"))
                    ->where('transaction_id', $value['id'])
                    ->where('technical_id', $this->selectTechnician)
                    ->whereMonth('created_at', $this->selectedMonth)
                    ->whereYear('created_at', $this->selectedYear);

                $dataFeeTechnicianTransactionItem = $queryTeknisiTransactionItem->get()->toArray();
                $dataFeeTechnician = [...$dataFeeTechnician, ...$dataFeeTechnicianTransactionItem];
            }

            $totalFeeTeknisi = array_sum(array_column($dataFeeTechnician, 'fee_teknisi'));
        }

        usort($dataFeeTechnician, function ($a, $b) {
            return strtotime($a['created_at']) <=> strtotime($b['created_at']);
        });

        return [
            'dataFeeTechnician' => $dataFeeTechnician,
            'totalFeeTeknisi' => $totalFeeTeknisi,
        ];
    }

    public function render()
    {
        $dataFeeTechnician = $this->getTechnician()['dataFeeTechnician'];
        $totalFeeTeknisi = $this->getTechnician()['totalFeeTeknisi'];

        $technician = Technician::all();

        $income = $this->getIncome()['income'];

        $totalIncome = $this->getIncome()['total_income'];

        $totalExpenditure = Expenditure::whereMonth('tanggal', $this->selectedMonth)
            ->whereYear('tanggal', $this->selectedYear)
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

    public function exportReport()
    {
        // TODO: Implement export functionality
        $this->dispatchBrowserEvent('swal', [
            'title' => 'Export Feature',
            'text' => 'Export functionality will be implemented soon',
            'icon' => 'info'
        ]);
    }
}
