<?php

namespace App\Http\Livewire\Dashboard\Reporting;

use App\Models\Expenditure as ModelsExpenditure;
use Carbon\Carbon;
use Livewire\Component;

class Expenditure extends Component
{

    public $isAdd = false;
    public $isEdit = false;

    public $currentId;
    public $tanggal;
    public $jenis;
    public $total;

    public $totalAmount;

    public $selectedYear = '';
    public $selectedMonth = '';

    protected $rules = [
        'tanggal' => 'required',
        'jenis' => 'required',
        'total' => 'required'
    ];

    public function resetValue()
    {
        $this->tanggal = '';
        $this->jenis = '';
        $this->total = '';
    }

    public function store()
    {
        $validateData = $this->validate();
        $currencyString = preg_replace("/[^0-9]/", "", $this->total);
        $convertedCurrency = (int)$currencyString;
        $validateData['total'] = $convertedCurrency;

        ModelsExpenditure::create($validateData);

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Success',
            'text' => "successfully expenditure created.",
            'icon' => 'success'
        ]);

        $this->setShowAdd();
    }

    public function edit($id)
    {
        $data = ModelsExpenditure::findOrFail($id);

        $this->tanggal = $data->tanggal;
        $this->jenis = $data->jenis;
        $this->total = $data->total;

        $this->currentId = $id;

        if (!$this->isEdit) {
            $this->setShowEdit();
        }
    }

    public function update()
    {
        $validateData = $this->validate();
        $currencyString = preg_replace("/[^0-9]/", "", $this->total);
        $convertedCurrency = (int)$currencyString;
        $validateData['total'] = $convertedCurrency;

        ModelsExpenditure::findOrFail($this->currentId)->update($validateData);

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Success',
            'text' => "successfully expenditure updated.",
            'icon' => 'success'
        ]);

        $this->setShowEdit();
    }

    public function delete($id)
    {
        ModelsExpenditure::findOrFail($id)->delete();

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Success',
            'text' => "successfully expenditure deleted.",
            'icon' => 'success'
        ]);
    }

    public function setShowAdd()
    {
        $this->isAdd = !$this->isAdd;
        $this->isEdit = false;
    }

    public function setShowEdit()
    {
        $this->isEdit = !$this->isEdit;
        $this->isAdd = false;
    }

    public function mount()
    {
        $this->selectedYear = Carbon::now()->format('Y');
        $this->selectedMonth = Carbon::now()->format('m');
    }

    public function render()
    {
        $query = ModelsExpenditure::whereMonth('tanggal', $this->selectedMonth)
            ->whereYear('tanggal', $this->selectedYear);
        $data = $query->get();
        $this->totalAmount = $data->sum('total');

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

        return view('livewire.dashboard.reporting.expenditure', ['data' => $data, 'listMonth' => $listMonth, 'listYear' => $listYear]);
    }
}
