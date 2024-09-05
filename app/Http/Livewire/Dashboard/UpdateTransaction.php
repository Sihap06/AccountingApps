<?php

namespace App\Http\Livewire\Dashboard;

use App\Http\Controllers\ProductController;
use App\Models\Customer;
use App\Models\LogActivityTransaction;
use App\Models\Product;
use App\Models\Technician;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class UpdateTransaction extends Component
{
    public $transactionId;
    public $serviceItems = [];
    public $customer_id;
    public $paymentMethod;
    public $orderDate;
    public $orderTransaction;

    public $isOpen = false;

    public $editId;
    public $editService;
    public $editBiaya;
    public $editTechnical;
    public $editProduct;

    public $service;
    public $biaya;
    public $technical;
    public $product;

    public $editAction = 'updateItemTransaction';
    public $formAction = '';

    protected $listeners = ['setSelected'];

    public function setSelected($value, $name)
    {
        $this->$name = $value;
    }

    public function setProduct($value)
    {
        $this->editProduct = $value;
    }

    public function updateIsEdit()
    {
        return redirect()->route('dashboard.reporting');
    }

    public function mount($id)
    {
        $this->transactionId = $id;
    }

    public function render()
    {
        $response = app(ProductController::class)->listProductAll();

        $inventory = Product::select(DB::raw("name as label"), DB::raw("id as value"))->get()->toArray();
        $technician = Technician::select(DB::raw("name as label"), DB::raw("id as value"))->get()->toArray();

        // dd($technician);
        $customers = Customer::all();
        $transaction = Transaction::findOrFail($this->transactionId);
        $transaction_items = TransactionItem::where('transaction_id', $this->transactionId)->get();
        $this->customer_id = $transaction['customer_id'];
        $this->paymentMethod = $transaction['payment_method'];
        $this->orderDate = Carbon::parse($transaction['created_at'])->format('Y-m-d');
        $this->orderTransaction = $transaction['order_transaction'];
        // dd($this->orderDate);

        return view('livewire.dashboard.update-transaction', compact('transaction', 'transaction_items', 'inventory', 'technician', 'customers'));
    }

    public function resetFieldValue()
    {
        $this->editBiaya = null;
        $this->editService = null;
        $this->editTechnical = null;
        $this->editProduct = null;

        $this->biaya = null;
        $this->service = null;
        $this->technical = null;
        $this->product = null;

        $this->formAction = '';

        $this->dispatchBrowserEvent('refreshSelect');
    }

    private function getPerhitungan($technical_id, $biaya, $modal)
    {
        if ($technical_id != null) {
            $tecnician = Technician::findOrFail($technical_id);
            $percentModal = $tecnician->percent_fee;
            $percentUntung = 100 - $percentModal;
            $countModal = $biaya * $percentModal / 100;
            $countUntung = $biaya * $percentUntung / 100;
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
            'service' => 'required',
            'biaya' => 'required',
            'technical' => '',
            'product' => '',
        ], [
            'biaya.required' => 'This field is required',
            'service.required' => 'This field is required',
        ]);

        if ($this->technical === '') {
            $this->technical = null;
        }

        if ($this->product === '') {
            $this->product = null;
        }

        $currencyString = preg_replace("/[^0-9]/", "", $this->biaya);
        $validateData['biaya'] = $currencyString;
        $validateData['service'] = $this->service;
        $validateData['technical_id'] = $this->technical;
        $validateData['product_id'] = $this->product;

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

        if ($validateData['product_id'] === '') {
            $validateData['product_id'] = null;
        }

        $transaction = Transaction::findOrFail($this->editId);

        if ($this->editService != $transaction->service || $validateData['biaya'] != $transaction->biaya || $validateData['technical_id'] != $transaction->technical_id || $validateData['product_id'] != $transaction->product_id) {
            $log = new LogActivityTransaction();
            $log->user = auth()->user()->name;
            $log->order_transaction = $transaction->order_transaction;
            $log->activity = 'update';

            if ($this->editService != $transaction->service) {
                $log->old_service = $transaction->service;
                $log->new_service = $this->editService;
            }

            if ($validateData['biaya'] != $transaction->biaya) {
                $log->old_biaya = $transaction->biaya;
                $log->new_biaya = $validateData['biaya'];
            }

            if ($validateData['technical_id'] != $transaction->technical_id) {
                $log->old_teknisi = $transaction->technical_id !== null ?  Technician::findOrFail($transaction->technical_id)->name : null;
                $log->new_teknisi = $validateData['technical_id'] != null ? Technician::findOrFail($validateData['technical_id'])->name : null;
            }

            if ($validateData['product_id'] != $transaction->product_id) {
                $log->old_sparepart = $transaction->product_id !== null ? Product::findOrFail($transaction->product_id)->name : null;
                $log->new_sparepart = $validateData['product_id'] !== null ? Product::findOrFail($validateData['product_id'])->name : null;
            }

            $log->save();
        }

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

            if ($validateData['product_id'] !== null) {
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


            $transaction->product_id = $validateData['product_id'];
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

        if ($validateData['product_id'] === '') {
            $validateData['product_id'] = null;
        }

        $transaction = TransactionItem::findOrFail($this->editId);

        if ($this->editService != $transaction->service || $validateData['biaya'] != $transaction->biaya || $validateData['technical_id'] != $transaction->technical_id || $validateData['product_id'] != $transaction->product_id) {
            $log = new LogActivityTransaction();
            $log->user = auth()->user()->name;
            $log->order_transaction = $this->orderTransaction;
            $log->activity = 'update';

            if ($this->editService != $transaction->service) {
                $log->old_service = $transaction->service;
                $log->new_service = $this->editService;
            }

            if ($validateData['biaya'] != $transaction->biaya) {
                $log->old_biaya = $transaction->biaya;
                $log->new_biaya = $validateData['biaya'];
            }

            if ($validateData['technical_id'] != $transaction->technical_id) {
                $log->old_teknisi = $transaction->technical_id !== null ?  Technician::findOrFail($transaction->technical_id)->name : null;
                $log->new_teknisi = $validateData['technical_id'] != null ? Technician::findOrFail($validateData['technical_id'])->name : null;
            }

            if ($validateData['product_id'] != $transaction->product_id) {
                $log->old_sparepart = $transaction->product_id !== null ? Product::findOrFail($transaction->product_id)->name : null;
                $log->new_sparepart = $validateData['product_id'] !== null ? Product::findOrFail($validateData['product_id'])->name : null;
            }

            $log->save();
        }

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

            if ($validateData['product_id'] !== null) {
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


            $transaction->product_id = $validateData['product_id'];
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


        if ($transaction->customer_id != $validateData['customer_id'] || $transaction->payment_method != $validateData['payment_method'] || Carbon::parse($transaction->created_at)->format('Y-m-d') != $order_date) {
            $log = new LogActivityTransaction();
            $log->user = auth()->user()->name;
            $log->order_transaction = $transaction->order_transaction;
            $log->activity = 'update';

            if ($transaction->customer_id != $validateData['customer_id']) {
                $log->old_customer = Customer::findOrFail($transaction->customer_id)->name;
                $log->new_customer = Customer::findOrFail($validateData['customer_id'])->name;
            }

            if ($transaction->payment_method != $validateData['payment_method']) {
                $log->old_payment_method = $transaction->payment_method;
                $log->new_payment_method = $validateData['payment_method'];
            }

            if (Carbon::parse($transaction->created_at)->format('Y-m-d') != $order_date) {
                $log->old_tanggal = Carbon::parse($transaction->created_at)->format('Y-m-d');
                $log->new_tanggal = $order_date;
            }

            $log->save();
        }



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
