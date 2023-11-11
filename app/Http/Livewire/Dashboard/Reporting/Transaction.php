<?php

namespace App\Http\Livewire\Dashboard\Reporting;

use App\Http\Controllers\ProductController;
use App\Models\Technician;
use App\Models\Transaction as ModelsTransaction;
use Livewire\Component;
use Livewire\WithPagination;

class Transaction extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $isEdit = false;
    public $inventory;
    public $technician;

    public $selectedId;

    public $biaya;
    public $service;
    public $product_id;
    public $technical_id;
    public $payment_method;
    public $order_transaction;

    protected $rules = [
        'biaya' => 'required',
        'order_transaction' => 'required',
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
        $data = ModelsTransaction::paginate(10);
        return view('livewire.dashboard.reporting.transaction', compact('data'));
    }

    public function edit($id)
    {
        $transaction = ModelsTransaction::findOrFail($id);

        $this->selectedId = $id;
        $this->order_transaction = $transaction->order_transaction;
        $this->service = $transaction->service;
        $this->biaya = $transaction->biaya;
        $this->product_id = $transaction->product_id;
        $this->technical_id = $transaction->technical_id;
        $this->payment_method = $transaction->payment_method;

        $this->isEdit = true;

        $this->dispatchBrowserEvent('appendField', ['order_transaction', 'teknisi', 'service', 'biaya', 'sparepart', 'metode_pembayaran']);
    }

    public function update()
    {
        $this->validate();

        $transaction = ModelsTransaction::findOrFail($this->selectedId);
        $transaction->order_transaction = $this->order_transaction;
        $transaction->service = $this->service;
        $transaction->biaya = $this->biaya;
        $transaction->payment_method = $this->payment_method;

        $transaction->save();

        $this->isEdit = false;

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Success',
            'text' => 'Successfully update transaction',
            'icon' => 'success'
        ]);
    }

    public function batal()
    {
        $this->isEdit = false;
    }
}
