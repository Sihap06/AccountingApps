<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class Customers extends Component
{
    use WithPagination;

    public $searchTerm;
    public $isOpen = false;
    public $modalType = 'store';
    public $customerId;
    public $name;
    public $no_telp;
    public $alamat;

    protected $rules = [
        'name' => 'required|string|max:255',
        'no_telp' => 'required|string|max:15',
        'alamat' => 'max:255',
    ];

    protected $messages = [
        'name.required' => 'This field is required.',
        'no_telp.required' => 'This field is required.',
        'no_telp.max' => 'This field maximal 15 character'
    ];

    public function openModal()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function resetField()
    {
        $this->reset(['name', 'no_telp', 'alamat']);
    }

    public function create()
    {
        $this->modalType = 'store';
        $this->resetField();
        $this->openModal();
    }

    public function store()
    {
        $this->validate();

        Customer::create([
            'name' => $this->name,
            'no_telp' => $this->no_telp,
            'alamat' => $this->alamat,
        ]);

        $this->closeModal();

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Customer Create',
            'text' => 'Customer successfully created.',
            'icon' => 'success'
        ]);

        $this->resetField();
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);

        $this->name = $customer->name;
        $this->no_telp = $customer->no_telp;
        $this->alamat = $customer->alamat;
        $this->customerId = $id;

        $this->modalType = 'update';
        $this->openModal();
    }

    public function update()
    {
        $this->validate();
        $customer = Customer::findOrFail($this->customerId);
        $customer->update([
            'name' => $this->name,
            'no_telp' => $this->no_telp,
            'alamat' => $this->alamat,
        ]);
        $this->closeModal();
        $this->dispatchBrowserEvent('swal', [
            'title' => 'Customer Update',
            'text' => 'Customer successfully updated.',
            'icon' => 'success'
        ]);
    }

    public function render()
    {
        $data = Customer::where(function ($sub_query) {
            $sub_query->where('name', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('no_telp', 'like', '%' . $this->searchTerm . '%');
        })
            ->orderby('name', 'ASC')
            ->paginate(10);
        return view('livewire.dashboard.customers', compact('data'))
            ->layout('components.layouts.dashboard');
    }

    public function delete($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        session()->flash('message', 'Customer successfully deleted.');

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Customer Delete',
            'text' => 'Customer successfully deleted.',
            'icon' => 'success'
        ]);
    }
}
