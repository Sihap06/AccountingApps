<?php

namespace App\Http\Livewire\Dashboard\Inventory;

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Livewire\Component;

class Create extends Component
{
    public $name;
    public $harga;
    public $stok;

    protected $rules = [
        'name' => 'required',
        'harga' => 'required',
        'stok' => 'required'
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function resetValue()
    {
        $this->name = '';
        $this->harga = '';
        $this->stok = '';
    }

    public function store()
    {
        $validateData = $this->validate();
        $currencyString = preg_replace("/[^0-9]/", "", $this->harga);

        $validateData["harga"] = (int)$currencyString;

        $request = new Request();
        $request->merge($validateData);


        $response = app(ProductController::class)->postProduct($request);
        $status = $response->getData(true)['status'];
        $message = $response->getData(true)['message'];

        $this->dispatchBrowserEvent('swal', [
            'title' => $status,
            'text' => $message,
            'icon' => $status
        ]);

        $this->resetValue();
    }

    public function render()
    {
        return view('livewire.dashboard.inventory.create')
            ->layout('components.layouts.dashboard');
    }
}
