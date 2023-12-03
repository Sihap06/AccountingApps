<?php

namespace App\Http\Livewire\Dashboard\Reporting;

use App\Http\Controllers\ProductController;
use App\Models\Technician;
use App\Models\Transaction as ModelsTransaction;
use Carbon\Carbon;
use Livewire\Component;

class Transaction extends Component
{

    public $isEdit = false;
    public $inventory = [];
    public $technician = [];

    public $selectedId;

    public $order_date;
    public $biaya;
    public $service;
    public $product_id;
    public $technical_id;
    public $payment_method;
    public $order_transaction;

    public $selectedDate;
    public $searchTerm = '';
    public $selectedPaymentMethod = '';

    public $totalBiaya = 0;


    protected $rules = [
        'biaya' => 'required',
        'order_transaction' => 'required',
        'service' => 'required',
        'product_id' => '',
        'technical_id' => '',
        'payment_method' => 'required',
        'order_date' => 'required'
    ];

    public function mount()
    {
        if (count($this->inventory) == 0 && count($this->technician) == 0) {
            $response = app(ProductController::class)->listProductAll();

            $this->inventory = $response->getData(true)['data'];
            $this->technician = Technician::all();
        }

        $this->selectedDate = Carbon::now()->format('Y-m-d');
    }

    public function render()
    {
        $data = ModelsTransaction::whereDate('created_at', $this->selectedDate)
            ->where(function ($sub_query) {
                $sub_query->where('order_transaction', 'like', '%' . $this->searchTerm . '%');
            });

        if ($this->selectedPaymentMethod !== '') {
            $data->where('payment_method', $this->selectedPaymentMethod);
        }

        if ($this->searchTerm !== '') {
            $data = ModelsTransaction::where(function ($sub_query) {
                $sub_query->where('order_transaction', 'like', '%' . $this->searchTerm . '%');
            })->get();
        } else {
            $data = $data->get();
        }

        $this->totalBiaya = $data->sum('biaya');


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
        $this->order_date = Carbon::parse($transaction->created_at)->format('Y-m-d');

        $this->isEdit = true;

        $this->dispatchBrowserEvent('appendField', ['order_transaction', 'teknisi', 'service', 'biaya', 'sparepart', 'metode_pembayaran']);
    }

    private function getPerhitungan($technical_id, $biaya, $modal)
    {
        if ($technical_id != null && $technical_id != '') {
            $countModal = $biaya * 40 / 100;
            $countUntung = $biaya * 60 / 100;
            return [
                'fee_teknisi' => $countModal,
                'modal' => $countModal,
                'untung' => $countUntung
            ];
        } else {
            return [
                'fee_teknisi' => 0,
                'modal' => ($modal != null) ? $modal : 0,
                'untung' => $biaya - $modal
            ];
        }
    }

    public function update()
    {
        $validateData = $this->validate();

        $time = Carbon::now()->format('H:i:s');
        $order_date = Carbon::parse($validateData['order_date'])->format('Y-m-d');

        $transaction = ModelsTransaction::findOrFail($this->selectedId);
        $transaction->order_transaction = $this->order_transaction;
        $transaction->service = $this->service;
        $transaction->biaya = $this->biaya;
        $transaction->payment_method = $this->payment_method;
        $transaction->product_id = $validateData['product_id'] === '' ? null : $validateData['product_id'];
        $transaction->technical_id = $validateData['technical_id'] === '' ? null : $validateData['technical_id'];
        $transaction->created_at = $order_date . ' ' . $time;

        $validateData['modal'] = 0;


        if ($validateData['product_id'] !== null && $validateData['product_id'] !== '') {
            $product = app(ProductController::class)->detailProduct((int)$validateData['product_id'])->getData(true)['data'];
            $validateData['modal'] = $product['harga'];
            $validateData['product_id'] = (int)$this->product_id;
        }

        $perhitungan = $this->getPerhitungan($validateData['technical_id'], $validateData['biaya'], $validateData['modal']);

        $transaction->modal = $perhitungan['modal'];
        $transaction->fee_teknisi  = $perhitungan['fee_teknisi'];
        $transaction->untung = $perhitungan['untung'];

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
