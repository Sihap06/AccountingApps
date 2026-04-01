<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Technician;
use Livewire\Component;
use Livewire\WithPagination;

class Teknisi extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $isAdd = false;
    public $isEdit = false;
    public $name;
    public $kode;
    public $percent_fee;
    public $percent_with_sparepart;
    public $currentId;

    protected $rules = [
        'name' => 'required',
        'kode' => 'required',
        'percent_fee' => 'required|numeric',
        'percent_with_sparepart' => 'required|numeric'
    ];

    protected $messages =  [
        'name.required' => 'Nama teknisi tidak boleh kosong',
        'kode.required' => 'Kode teknisi tidak boleh kosong',
        'percent_fee.required' => 'Persentase fee tidak boleh kosong',
        'percent_fee.numeric' => 'Persentase fee harus berupa angka',
        'percent_with_sparepart.required' => 'Persentase with sparepart tidak boleh kosong',
        'percent_with_sparepart.numeric' => 'Persentase with sparepart harus berupa angka'
    ];

    public function setShowAdd()
    {
        $this->isAdd = !$this->isAdd;
    }

    public function setShowEdit()
    {
        $this->isEdit = !$this->isEdit;
    }

    public function store()
    {
        $validateData = $this->validate();

        Technician::create($validateData);

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Success',
            'text' => "successfully technician created.",
            'icon' => 'success'
        ]);

        $this->setShowAdd();
    }

    public function edit($id)
    {
        $data = Technician::findOrFail($id);
        $this->name = $data->name;
        $this->kode = $data->kode;
        $this->percent_fee = $data->percent_fee;
        $this->percent_with_sparepart = $data->percent_with_sparepart;

        $this->currentId = $id;

        if (!$this->isEdit) {
            $this->setShowEdit();
        }
    }

    public function update()
    {
        $validateData = $this->validate();

        Technician::findOrFail($this->currentId)->update($validateData);

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Success',
            'text' => "successfully technician updated.",
            'icon' => 'success'
        ]);

        $this->setShowEdit();
    }

    public function delete($id)
    {
        Technician::findOrFail($id)->delete();

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Success',
            'text' => "successfully technician deleted.",
            'icon' => 'success'
        ]);
    }

    public function render()
    {
        $data = Technician::paginate(10);
        return view('livewire.dashboard.teknisi', compact('data'))
            ->layout('components.layouts.dashboard');
    }
}
