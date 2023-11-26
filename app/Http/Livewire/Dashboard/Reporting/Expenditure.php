<?php

namespace App\Http\Livewire\Dashboard\Reporting;

use App\Models\Expenditure as ModelsExpenditure;
use Livewire\Component;
use Livewire\WithPagination;

class Expenditure extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $isAdd = false;
    public $isEdit = false;

    public $currentId;
    public $tanggal;
    public $jenis;
    public $total;

    public $totalAmount;

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

    public function render()
    {
        $component_id = $this->id;
        $data = ModelsExpenditure::all();
        $this->totalAmount = ModelsExpenditure::sum('total');
        return view('livewire.dashboard.reporting.expenditure', compact('data', 'component_id'));
    }
}
