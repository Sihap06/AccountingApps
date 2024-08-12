<?php

namespace App\Http\Livewire\Dashboard;

use App\Http\Controllers\ProductController;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Technician;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Livewire\Component;

class UpdateTransaction extends Component
{
    public $transactionId;
    public $inventory;
    public $technician;
    public $customers;
    public $serviceItems = [];
    public $customer_id;
    public $paymentMethod;
    public $orderDate;

    public $isOpen = false;

    public $editId;
    public $editService;
    public $editBiaya;
    public $editTechnical;
    public $editProduct;

    public $editAction = 'updateItemTransaction';
    public $formAction = 'add';

    public function updateIsEdit()
    {
        return redirect()->route('dashboard.reporting');
    }

    public function mount($id)
    {
        $this->transactionId = $id;
        $response = app(ProductController::class)->listProductAll();

        $this->inventory = $response->getData(true)['data'];
        $this->technician = Technician::all();
        $this->customers = Customer::all();
    }

    public function render()
    {
        $transaction = Transaction::findOrFail($this->transactionId);
        $transaction_items = TransactionItem::where('transaction_id', $this->transactionId)->get();
        $this->customer_id = $transaction['customer_id'];
        $this->paymentMethod = $transaction['payment_method'];
        $this->orderDate = Carbon::parse($transaction['created_at'])->format('Y-m-d');
        // dd($this->orderDate);

        return view('livewire.dashboard.update-transaction', compact('transaction', 'transaction_items'));
    }

    public function resetFieldValue()
    {
        $this->editBiaya = null;
        $this->editService = null;
        $this->editTechnical = null;
        $this->editProduct = null;

        $this->dispatchBrowserEvent('setSelectedValue', ['editTechnical' => $this->editTechnical, 'editProduct' => $this->editProduct]);
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

    public function addServiceItem()
    {
        $validateData = $this->validate([
            'editService' => 'required',
            'editBiaya' => 'required',
            'editTechnical' => '',
            'editProduct' => '',
        ], [
            'editBiaya.required' => 'This field is required',
            'editService.required' => 'This field is required',
        ]);

        if ($this->editTechnical === '') {
            $this->editTechnical = null;
        }

        if ($this->editProduct === '') {
            $this->editProduct = null;
        }

        $currencyString = preg_replace("/[^0-9]/", "", $this->editBiaya);
        $validateData['biaya'] = $currencyString;
        $validateData['service'] = $this->editService;
        $validateData['technical_id'] = $this->editTechnical;
        $validateData['product_id'] = $this->editProduct;

        $newTransactionItem = new TransactionItem();
        $newTransactionItem->transaction_id = $this->transactionId;

        $validateData['modal'] = 0;

        if ($validateData['product_id'] !== null) {
            $product = Product::findOrFail($validateData['product_id']);
            if ($product->stok < 1) {
                return $this->dispatchBrowserEvent('swal', [
                    'title' => 'Error',
                    'text' => 'product stock is insufficient',
                    'icon' => 'error'
                ]);
            }

            $newTransactionItem->product_id = $validateData['product_id'];
            $validateData['modal'] = $product->harga;
            $validateData['technical_id'] = null;
        } elseif ($validateData['technical_id'] !== null) {
            $validateData['modal'] = 0;
            $validateData['product_id'] = null;
        }

        $newTransactionItem->technical_id = $validateData['technical_id'];
        $newTransactionItem->service = $validateData['service'];
        $newTransactionItem->biaya = $validateData['biaya'];

        $perhitungan = $this->getPerhitungan($validateData['technical_id'], $validateData['biaya'], $validateData['modal']);

        $newTransactionItem->modal = $perhitungan['modal'];
        $newTransactionItem->fee_teknisi  = $perhitungan['fee_teknisi'];
        $newTransactionItem->untung = $perhitungan['untung'];

        $newTransactionItem->save();

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Success',
            'text' => 'Successfully add item transaction',
            'icon' => 'success'
        ]);

        $this->formAction = 'add';
        $this->resetFieldValue();
    }

    public function removeItem($itemId)
    {
        $transactionItem = TransactionItem::find($itemId);
        $transactionItem->delete();

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Success',
            'text' => 'Successfully delete item transaction',
            'icon' => 'success'
        ]);
    }

    public function editItemTransaction($id)
    {
        $data = Transaction::findOrFail($id);
        $this->editService = $data->service;
        $this->editBiaya = $data->biaya;
        $this->editTechnical = $data->technical_id;
        $this->editProduct = $data->product_id;

        $this->editId = $id;

        $this->formAction = 'edit';
        $this->editAction = 'updateItemTransaction';
        $this->dispatchBrowserEvent('appendField', ['editService', 'editBiaya']);
        $this->dispatchBrowserEvent('setSelectedValue', ['editTechnical' => $data->technical_id, 'editProduct' => $data->product_id]);
    }

    public function editItem($id)
    {
        $data = TransactionItem::findOrFail($id);
        $this->editService = $data->service;
        $this->editBiaya = $data->biaya;
        $this->editTechnical = $data->technical_id;
        $this->editProduct = $data->product_id;

        $this->editId = $id;

        $this->formAction = 'edit';
        $this->editAction = 'updateItem';
        $this->dispatchBrowserEvent('appendField', ['editService', 'editBiaya']);
        $this->dispatchBrowserEvent('setSelectedValue', ['editTechnical' => $data->technical_id, 'editProduct' => $data->product_id]);
    }

    public function updateItemTransaction()
    {
        $validateData = $this->validate([
            'editService' => 'required',
            'editBiaya' => 'required',
            'editTechnical' => '',
            'editProduct' => '',
        ], [
            'editBiaya.required' => 'This field is required',
            'editService.required' => 'This field is required',
        ]);

        $currencyString = preg_replace("/[^0-9]/", "", $this->editBiaya);
        $validateData['biaya'] = $currencyString;
        $validateData['technical_id'] = $this->editTechnical;
        $validateData['product_id'] = $this->editProduct;

        $transaction = Transaction::findOrFail($this->editId);
        $transaction->service = $this->editService;
        $transaction->biaya = $validateData['biaya'];
        $transaction->technical_id = $validateData['technical_id'] === '' ? null : $validateData['technical_id'];

        $validateData['modal'] = 0;

        if ($validateData['product_id'] != $transaction->product_id) {
            if ($transaction->product_id !== null) {
                $currentProduct = Product::findOrFail($transaction->product_id);
                $currentProduct->stok = $currentProduct->stok + 1;
                $currentProduct->save();
            }

            if ($validateData['product_id'] !== null && $validateData['product_id'] !== '') {
                $newProduct = Product::findOrFail($validateData['product_id']);
                // dd($newProduct['stock']);
                if ($newProduct->stok < 1) {
                    return $this->dispatchBrowserEvent('swal', [
                        'title' => 'Error',
                        'text' => 'product stock is insufficient',
                        'icon' => 'error'
                    ]);
                }
                $newProduct->stok = $newProduct->stok - 1;
                $newProduct->save();

                $validateData['modal'] = $newProduct->harga;
            }


            $transaction->product_id = $validateData['product_id'] === '' ? null : $validateData['product_id'];
        }

        $perhitungan = $this->getPerhitungan($validateData['technical_id'], $validateData['biaya'], $validateData['modal']);

        $transaction->modal = $perhitungan['modal'];
        $transaction->fee_teknisi  = $perhitungan['fee_teknisi'];
        $transaction->untung = $perhitungan['untung'];
        $transaction->save();

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Success',
            'text' => 'Successfully update item transaction',
            'icon' => 'success'
        ]);

        $this->formAction = 'add';
        $this->resetFieldValue();
    }

    public function updateItem()
    {
        $validateData = $this->validate([
            'editService' => 'required',
            'editBiaya' => 'required',
            'editTechnical' => '',
            'editProduct' => '',
        ], [
            'editBiaya.required' => 'This field is required',
            'editService.required' => 'This field is required',
        ]);

        $currencyString = preg_replace("/[^0-9]/", "", $this->editBiaya);
        $validateData['biaya'] = $currencyString;
        $validateData['technical_id'] = $this->editTechnical;
        $validateData['product_id'] = $this->editProduct;

        $transaction = TransactionItem::findOrFail($this->editId);
        $transaction->service = $this->editService;
        $transaction->biaya = $validateData['biaya'];
        $transaction->technical_id = $validateData['technical_id'] === '' ? null : $validateData['technical_id'];

        $validateData['modal'] = 0;

        if ($validateData['product_id'] != $transaction->product_id) {
            if ($transaction->product_id !== null) {
                $currentProduct = Product::findOrFail($transaction->product_id);
                $currentProduct->stok = $currentProduct->stok + 1;
                $currentProduct->save();
            }

            if ($validateData['product_id'] !== null && $validateData['product_id'] !== '') {
                $newProduct = Product::findOrFail($validateData['product_id']);
                // dd($newProduct['stock']);
                if ($newProduct->stok < 1) {
                    return $this->dispatchBrowserEvent('swal', [
                        'title' => 'Error',
                        'text' => 'product stock is insufficient',
                        'icon' => 'error'
                    ]);
                }
                $newProduct->stok = $newProduct->stok - 1;
                $newProduct->save();

                $validateData['modal'] = $newProduct->harga;
            }


            $transaction->product_id = $validateData['product_id'] === '' ? null : $validateData['product_id'];
        }

        $perhitungan = $this->getPerhitungan($validateData['technical_id'], $validateData['biaya'], $validateData['modal']);

        $transaction->modal = $perhitungan['modal'];
        $transaction->fee_teknisi  = $perhitungan['fee_teknisi'];
        $transaction->untung = $perhitungan['untung'];
        $transaction->save();

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Success',
            'text' => 'Successfully update item transaction',
            'icon' => 'success'
        ]);

        $this->formAction = 'add';
        $this->resetFieldValue();
    }

    public function updateTransaction()
    {
        $validateData = $this->validate([
            'customer_id' => 'required',
            'paymentMethod' => 'required',
            'orderDate' => 'required'
        ], [
            'customer_id.required' => 'Customer ID is required',
            'paymentMethod.required' => 'Payment Method is required',
            'orderDate.required' => 'Order Date is required'
        ]);

        $validateData['payment_method'] = $this->paymentMethod;
        $validateData['order_date'] = $this->orderDate;

        $time = Carbon::now()->format('H:i:s');
        $order_date = Carbon::parse($validateData['order_date'])->format('Y-m-d');

        $transaction = Transaction::findOrFail($this->transactionId);
        $transaction->customer_id = $this->customer_id;
        $transaction->payment_method = $validateData['payment_method'];
        $transaction->created_at = $order_date . ' ' . $time;
        $transaction->save();

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Success',
            'text' => 'Successfully update transaction',
            'icon' => 'success'
        ]);

        return redirect()->route('dashboard.reporting');
    }
}
