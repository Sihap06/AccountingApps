<?php

namespace App\Http\Livewire\Dashboard;

use App\Http\Controllers\ProductController;
use App\Models\Customer;
use App\Models\LogActivityTransaction;
use App\Models\LogActivityProduct;
use App\Models\Product;
use App\Models\Technician;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\PendingChange;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UpdateTransaction extends Component
{
    public $transactionId;
    public $customer_id;
    public $paymentMethod;
    public $orderDate;
    public $orderTransaction;
    public $warranty = '';
    public $warranty_type = 'daily';

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

    public $reason = '';
    public $showReasonModal = false;
    public $pendingAction = null;
    public $pendingActionData = null;

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
            'warranty' => '',
            'warranty_type' => ''
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

        $warranty = '';
        $warranty_type = null;

        if ($this->warranty != 0 || $this->warranty != '') {
            $warranty = $this->warranty;
            $warranty_type = $this->warranty_type;
        }

        $currencyString = preg_replace("/[^0-9]/", "", $this->biaya);
        $validateData['biaya'] = $currencyString;
        $validateData['service'] = $this->service;
        $validateData['technical_id'] = $this->technical;
        $validateData['product_id'] = $this->product;

        // Check if user is sysadmin (operator) - needs verification
        if (Auth::user()->role === 'sysadmin') {
            // Store data and show reason modal
            $this->pendingAction = 'addServiceItem';
            $this->pendingActionData = [
                'validateData' => $validateData,
                'warranty' => $warranty,
                'warranty_type' => $warranty_type
            ];
            $this->showReasonModal = true;
            return; // Return early
        }

        // Master admin can add directly

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
        $newTransactionItem->warranty = $warranty;
        $newTransactionItem->warranty_type = $warranty_type;

        $newTransactionItem->save();

        // Deduct stock if product was used
        if ($validateData['product_id'] !== null) {
            $product = Product::findOrFail($validateData['product_id']);
            $oldStock = $product->stok;

            // Set bypass flag to skip verification for transaction stock updates
            $product->bypassVerification = true;

            $product->stok = $product->stok - 1;
            $product->save();

            // Log stock usage
            $log = new LogActivityProduct();
            $log->user = Auth::user()->name;
            $log->activity = 'update';
            $log->product = $product->name;
            $log->old_stok = $oldStock;
            $log->new_stok = $product->stok;
            $log->save();
        }

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

        // Check if user is sysadmin (operator) - needs verification
        if (Auth::user()->role === 'sysadmin') {
            // Store data and show reason modal
            $this->pendingAction = 'removeItem';
            $this->pendingActionData = [
                'itemId' => $itemId,
                'transactionItem' => $transactionItem->toArray()
            ];
            $this->showReasonModal = true;
            return; // Return early
        }

        // Master admin can delete directly
        // Return stock if product was used
        if ($transactionItem->product_id) {
            $product = Product::find($transactionItem->product_id);
            if ($product) {
                $oldStock = $product->stok;

                // Set bypass flag to skip verification for transaction stock updates
                $product->bypassVerification = true;

                $product->stok = $product->stok + 1;
                $product->save();

                // Log stock return
                $log = new LogActivityProduct();
                $log->user = Auth::user()->name;
                $log->activity = 'update';
                $log->product = $product->name;
                $log->old_stok = $oldStock;
                $log->new_stok = $product->stok;
                $log->save();
            }
        }

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
        $this->warranty = $data->warranty;
        $this->warranty_type = $data->warranty_type;

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
        $this->warranty = $data->warranty;
        $this->warranty_type = $data->warranty_type;

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

        // Check if user is sysadmin (operator) - needs verification
        if (Auth::user()->role === 'sysadmin') {
            // Store data and show reason modal
            $this->pendingAction = 'updateItemTransaction';
            $this->pendingActionData = [
                'transaction' => $transaction->toArray(),
                'validateData' => $validateData,
                'editService' => $this->editService,
                'warranty' => $this->warranty,
                'warranty_type' => $this->warranty_type
            ];
            $this->showReasonModal = true;
            $this->formAction = ''; // Close edit form
            return; // Return early
        }

        // Master admin can update directly
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

        $validateData['modal'] = $transaction->modal;

        if ($validateData['product_id'] != $transaction->product_id) {
            if ($transaction->product_id !== null) {
                $currentProduct = Product::findOrFail($transaction->product_id);
                $oldStock = $currentProduct->stok;

                // Set bypass flag to skip verification for transaction stock updates
                $currentProduct->bypassVerification = true;

                $currentProduct->stok = $currentProduct->stok + 1;
                $currentProduct->save();

                // Log stock return
                $log = new LogActivityProduct();
                $log->user = Auth::user()->name;
                $log->activity = 'transaction stock return';
                $log->product = $currentProduct->name;
                $log->old_stok = $oldStock;
                $log->new_stok = $currentProduct->stok;
                $log->save();
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

                $oldStock = $newProduct->stok;

                // Set bypass flag to skip verification for transaction stock updates
                $newProduct->bypassVerification = true;

                $newProduct->stok = $newProduct->stok - 1;
                $newProduct->save();

                // Log stock usage
                $log = new LogActivityProduct();
                $log->user = Auth::user()->name;
                $log->activity = 'transaction stock update';
                $log->product = $newProduct->name;
                $log->old_stok = $oldStock;
                $log->new_stok = $newProduct->stok;
                $log->save();

                $validateData['modal'] = $newProduct->harga;
            }


            $transaction->product_id = $validateData['product_id'];
        }

        $perhitungan = $this->getPerhitungan($validateData['technical_id'], $validateData['biaya'], $validateData['modal']);

        $transaction->modal = $perhitungan['modal'];
        $transaction->fee_teknisi  = $perhitungan['fee_teknisi'];
        $transaction->untung = $perhitungan['untung'];
        $transaction->warranty = $this->warranty;
        $transaction->warranty_type = $this->warranty_type;

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

        // Check if user is sysadmin (operator) - needs verification
        if (Auth::user()->role === 'sysadmin') {
            // Store data and show reason modal
            $this->pendingAction = 'updateItem';
            $this->pendingActionData = [
                'transactionItem' => $transaction->toArray(),
                'validateData' => $validateData,
                'editService' => $this->editService,
                'warranty' => $this->warranty,
                'warranty_type' => $this->warranty_type
            ];
            $this->showReasonModal = true;
            $this->formAction = ''; // Close edit form
            return; // Return early
        }

        // Master admin can update directly
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
                $oldStock = $currentProduct->stok;

                // Set bypass flag to skip verification for transaction stock updates
                $currentProduct->bypassVerification = true;

                $currentProduct->stok = $currentProduct->stok + 1;
                $currentProduct->save();

                // Log stock return
                $log = new LogActivityProduct();
                $log->user = Auth::user()->name;
                $log->activity = 'transaction stock return';
                $log->product = $currentProduct->name;
                $log->old_stok = $oldStock;
                $log->new_stok = $currentProduct->stok;
                $log->save();
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

                $oldStock = $newProduct->stok;

                // Set bypass flag to skip verification for transaction stock updates
                $newProduct->bypassVerification = true;

                $newProduct->stok = $newProduct->stok - 1;
                $newProduct->save();

                // Log stock usage
                $log = new LogActivityProduct();
                $log->user = Auth::user()->name;
                $log->activity = 'transaction stock update';
                $log->product = $newProduct->name;
                $log->old_stok = $oldStock;
                $log->new_stok = $newProduct->stok;
                $log->save();

                $validateData['modal'] = $newProduct->harga;
            }


            $transaction->product_id = $validateData['product_id'];
        }

        $perhitungan = $this->getPerhitungan($validateData['technical_id'], $validateData['biaya'], $validateData['modal']);

        $transaction->modal = $perhitungan['modal'];
        $transaction->fee_teknisi  = $perhitungan['fee_teknisi'];
        $transaction->untung = $perhitungan['untung'];
        $transaction->warranty = $this->warranty;
        $transaction->warranty_type = $this->warranty_type;
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

        // Check if user is sysadmin (operator) - needs verification
        if (Auth::user()->role === 'sysadmin') {
            // Store data and show reason modal
            $this->pendingAction = 'updateTransaction';
            $this->pendingActionData = [
                'transaction' => $transaction->toArray(),
                'newData' => [
                    'customer_id' => $this->customer_id,
                    'payment_method' => $validateData['payment_method'],
                    'created_at' => $order_date . ' ' . $time,
                ]
            ];
            $this->showReasonModal = true;
            return; // Return early to prevent redirect
        } else {
            // Master admin can update directly
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
                'text' => 'Successfully updated transaction',
                'icon' => 'success'
            ]);
        }

        return redirect()->route('dashboard.reporting');
    }

    public function submitReason()
    {
        $this->validate([
            'reason' => 'required|min:10'
        ], [
            'reason.required' => 'Alasan harus diisi',
            'reason.min' => 'Alasan minimal 10 karakter'
        ]);

        if ($this->pendingAction === 'updateTransaction') {
            $transaction = $this->pendingActionData['transaction'];
            $newData = array_merge($transaction, $this->pendingActionData['newData']);

            // Create pending change with reason
            PendingChange::create([
                'changeable_type' => Transaction::class,
                'changeable_id' => $this->transactionId,
                'action' => 'update',
                'old_data' => $transaction,
                'new_data' => $newData,
                'reason' => $this->reason,
                'requested_by' => Auth::user()->id,
                'requested_at' => now(),
            ]);

            $this->dispatchBrowserEvent('swal', [
                'title' => 'Success',
                'text' => 'Perubahan berhasil disimpan dan menunggu verifikasi.',
                'icon' => 'info'
            ]);

            // Redirect after submission
            return redirect()->route('dashboard.reporting');
        } elseif ($this->pendingAction === 'updateItemTransaction') {
            $transaction = $this->pendingActionData['transaction'];
            $validateData = $this->pendingActionData['validateData'];

            // Prepare new data
            $newData = $transaction;
            $newData['service'] = $this->pendingActionData['editService'];
            $newData['biaya'] = $validateData['biaya'];
            $newData['technical_id'] = $validateData['technical_id'];
            $newData['product_id'] = $validateData['product_id'];
            $newData['warranty'] = $this->pendingActionData['warranty'];
            $newData['warranty_type'] = $this->pendingActionData['warranty_type'];

            // Create pending change with reason
            PendingChange::create([
                'changeable_type' => Transaction::class,
                'changeable_id' => $this->editId,
                'action' => 'update',
                'old_data' => $transaction,
                'new_data' => $newData,
                'reason' => $this->reason,
                'requested_by' => Auth::user()->id,
                'requested_at' => now(),
            ]);

            $this->dispatchBrowserEvent('swal', [
                'title' => 'Success',
                'text' => 'Perubahan berhasil disimpan dan menunggu verifikasi.',
                'icon' => 'info'
            ]);
        } elseif ($this->pendingAction === 'updateItem') {
            $transactionItem = $this->pendingActionData['transactionItem'];
            $validateData = $this->pendingActionData['validateData'];

            // Prepare new data
            $newData = $transactionItem;
            $newData['service'] = $this->pendingActionData['editService'];
            $newData['biaya'] = $validateData['biaya'];
            $newData['technical_id'] = $validateData['technical_id'];
            $newData['product_id'] = $validateData['product_id'];
            $newData['warranty'] = $this->pendingActionData['warranty'];
            $newData['warranty_type'] = $this->pendingActionData['warranty_type'];

            // Create pending change with reason
            PendingChange::create([
                'changeable_type' => TransactionItem::class,
                'changeable_id' => $this->editId,
                'action' => 'update',
                'old_data' => $transactionItem,
                'new_data' => $newData,
                'reason' => $this->reason,
                'requested_by' => Auth::user()->id,
                'requested_at' => now(),
            ]);

            $this->dispatchBrowserEvent('swal', [
                'title' => 'Success',
                'text' => 'Perubahan berhasil disimpan dan menunggu verifikasi.',
                'icon' => 'info'
            ]);
        } elseif ($this->pendingAction === 'removeItem') {
            $itemId = $this->pendingActionData['itemId'];
            $transactionItem = $this->pendingActionData['transactionItem'];

            // Create pending change with reason for deletion
            PendingChange::create([
                'changeable_type' => TransactionItem::class,
                'changeable_id' => $itemId,
                'action' => 'delete',
                'old_data' => $transactionItem,
                'new_data' => null,
                'reason' => $this->reason,
                'requested_by' => Auth::user()->id,
                'requested_at' => now(),
            ]);

            $this->dispatchBrowserEvent('swal', [
                'title' => 'Success',
                'text' => 'Permintaan penghapusan berhasil disimpan dan menunggu verifikasi.',
                'icon' => 'info'
            ]);
        } elseif ($this->pendingAction === 'addServiceItem') {
            $validateData = $this->pendingActionData['validateData'];
            $warranty = $this->pendingActionData['warranty'];
            $warranty_type = $this->pendingActionData['warranty_type'];

            // Prepare new transaction item data
            $newItemData = [
                'transaction_id' => $this->transactionId,
                'service' => $validateData['service'],
                'biaya' => $validateData['biaya'],
                'technical_id' => $validateData['technical_id'],
                'product_id' => $validateData['product_id'],
                'warranty' => $warranty,
                'warranty_type' => $warranty_type
            ];

            // Create pending change with reason for creation
            PendingChange::create([
                'changeable_type' => TransactionItem::class,
                'changeable_id' => null, // No ID yet as it's a new item
                'action' => 'create',
                'old_data' => null,
                'new_data' => $newItemData,
                'reason' => $this->reason,
                'requested_by' => Auth::user()->id,
                'requested_at' => now()
            ]);

            $this->dispatchBrowserEvent('swal', [
                'title' => 'Success',
                'text' => 'Permintaan penambahan item berhasil disimpan dan menunggu verifikasi.',
                'icon' => 'info'
            ]);
        }

        $this->closeReasonModal();
        $this->resetFieldValue();
    }

    public function closeReasonModal()
    {
        $this->showReasonModal = false;
        $this->reason = '';
        $this->pendingAction = null;
        $this->pendingActionData = null;
    }
}
