<?php

namespace App\Http\Livewire\Dashboard;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PointOfSales extends Component
{
    public $biaya;
    public $service;
    public $product_id;
    public $technical_id;
    public $payment_method;
    public $order_id;

    public $inventory;
    public $technician;

    protected $rules = [
        'biaya' => 'required',
        'order_id' => 'required',
        'service' => 'required',
        'product_id' => '',
        'technical_id' => '',
        'payment_method' => 'required'
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

    public function resetValue()
    {
        $this->biaya = "";
        $this->order_id = "";
        $this->service = "";
        $this->product_id = "";
        $this->technical_id = "";
        $this->payment_method = "";

        $this->dispatchBrowserEvent('resetField', ['teknisi', 'metode_pembayaran', 'sparepart']);
    }

    public function submit()
    {
        $validateData = $this->validate();

        $currencyString = preg_replace("/[^0-9]/", "", $this->biaya);

        $validateData['biaya'] = $currencyString;
        $validateData['order_transaction'] = '00001';
        $validateData['created_by'] = Auth::user()->id;

        if ($validateData['product_id'] !== null) {
            $product = app(ProductController::class)->detailProduct((int)$validateData['product_id'])->getData(true)['data'];
            $validateData['modal'] = $product['harga'];
            $validateData['product_id'] = (int)$this->product_id;
            $validateData['technical_id'] = null;
        } elseif ($validateData['technical_id'] !== null) {
            $validateData['modal'] = 0;
            $validateData['product_id'] = null;
        }

        $request = new Request();
        $request->merge($validateData);

        $response = app(TransactionController::class)->postTransaction($request);
        $status = $response->getData(true)['status'];
        $message = $response->getData(true)['message'];

        $this->dispatchBrowserEvent('swal', [
            'title' => $status,
            'text' => $message,
            'icon' => $status
        ]);

        $this->resetValue();
    }
}
