<?php

namespace App\Http\Livewire\Dashboard\Inventory;

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Livewire\Component;

class Edit extends Component
{
    public $name;
    public $harga;
    public $current_stok;
    public $stok;


    public function mount($id)
    {
        $response = app(ProductController::class)->detailProduct($id);

        $data = $response->getData(true)['data'];
        $this->name = $data['name'];
        $this->harga = $data['harga'];
        $this->current_stok = $data['stok'];
    }

    public function render()
    {
        return view('livewire.dashboard.inventory.edit')
            ->layout('components.layouts.dashboard');
    }

    public function update()
    {
        $validateData = $this->validate();
        $currencyString = preg_replace("/[^0-9]/", "", $this->harga);

        $validateData["harga"] = (int)$currencyString;

        $request = new Request();
        $request->merge($validateData);


        app(ProductController::class)->postProduct($request);

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Success',
            'text' => "successfully inventory created.",
            'icon' => 'success'
        ]);

        $this->resetValue();
    }
}
