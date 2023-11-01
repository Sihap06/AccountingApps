<?php

namespace App\Http\Livewire\Dashboard;

use App\Http\Controllers\ProductController;
use App\Models\Technician;
use Livewire\Component;

class PointOfSales extends Component
{
    public $biaya;
    public $service;
    public $modal;
    public $product_id;
    public $technical_id;
    public $metode_pembayaran;

    public $inventory;
    public $technician;

    protected $rules = [
        'biaya' => 'required',
        'service' => 'required',
        'modal' => 'required',
        'product_id' => 'required',
        'technical_id' => 'required',
        'metode_pembayaran' => 'required'
    ];

    public function mount()
    {
        $response = app(ProductController::class)->listProductAll();

        $this->inventory = $response->getData(true)['data'];
        $this->technician = Technician::all();
    }

    public function render()
    {
        return view('livewire.dashboard.point-of-sales')
            ->layout('components.layouts.dashboard');
    }

    public function submit()
    {
        $validateData = $this->validate();
        dd($validateData);
        $currencyString = preg_replace("/[^0-9]/", "", $this->biaya);
        // dd($currencyString);

        $this->dispatchBrowserEvent('swal', [
            'text' => 'This is a success message!',
            'title' => 'Kembalian',

        ]);
    }
}
