<?php

namespace App\Http\Livewire\Dashboard\Inventory;

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Livewire\Component;

class Edit extends Component
{
    public $current_id;
    public $name;
    public $harga;
    public $current_stok;
    public $stok;

    protected $rules = [
        'name' => 'required',
        'harga' => 'required',
        'stok' => 'required',
    ];

    public function mount($id)
    {
        $response = app(ProductController::class)->detailProduct($id);

        $data = $response->getData(true)['data'];
        $this->current_id = $data['id'];
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
        $validateData["stok"] = (int)$this->current_stok + (int)$this->stok;

        $request = new Request();
        $request->merge($validateData);


        $res = app(ProductController::class)->upadteProduct($request, $this->current_id);

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Success',
            'text' => $res->getData(true)['message'],
            'icon' => 'success'
        ]);

        // $this->resetValue();
    }
}
