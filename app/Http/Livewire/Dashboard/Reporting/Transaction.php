<?php

namespace App\Http\Livewire\Dashboard\Reporting;

use App\Http\Controllers\ProductController;
use App\Models\Product;
use App\Models\Technician;
use App\Models\Transaction as ModelsTransaction;
use App\Models\TransactionItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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

    protected $listeners = ['updateIsEdit' => 'handleUpdateIsEdit', 'refreshComponent' => '$refresh'];


    public function handleUpdateIsEdit($isEdit)
    {
        $this->isEdit = $isEdit;
    }


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
        $data = ModelsTransaction::leftJoin('transaction_items', function ($join) {
            $join->on('transactions.id', '=', 'transaction_items.transaction_id')
                ->whereNull('transaction_items.deleted_at');
        })
            ->leftJoin('customers', 'customers.id', '=', 'transactions.customer_id')
            ->select(
                'transactions.created_at',
                'customers.name as customer_name',
                'transactions.id',
                'transactions.order_transaction',
                'transactions.service',
                DB::raw('GROUP_CONCAT(transaction_items.service SEPARATOR ", ") as service_name'),
                DB::raw('transactions.biaya as first_item_biaya'), // Biaya item pertama dari transactions
                DB::raw('SUM(transaction_items.biaya) as other_items_biaya'), // Biaya untuk item lainnya dari transaction_items
                DB::raw('transactions.modal as first_item_modal'), // Modal item pertama dari transactions
                DB::raw('SUM(transaction_items.modal) as other_items_modal'), // Modal untuk item lainnya dari transaction_items
                DB::raw('transactions.biaya + IFNULL(SUM(transaction_items.biaya), 0) as total_biaya'), // Total biaya
                'transactions.payment_method',
            )
            ->where('transactions.status', 'done')
            ->whereNull('transactions.deleted_at')
            ->groupBy(
                'transactions.created_at',
                'customers.name',
                'transactions.id',
                'transactions.order_transaction',
                'transactions.biaya',
                'transactions.modal',
                'transactions.payment_method'
            );

        // Menambahkan filter untuk payment_method jika ada
        if ($this->selectedPaymentMethod !== '') {
            $data->where('transactions.payment_method', $this->selectedPaymentMethod);
        }

        // Menambahkan filter untuk searchTerm jika ada
        if ($this->searchTerm !== '') {
            $data->where(function ($sub_query) {
                $sub_query->where('transactions.order_transaction', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('customers.name', 'like', '%' . $this->searchTerm . '%');
            });
        } else {
            $data->whereDate('transactions.created_at', $this->selectedDate);
        }

        // Mengambil hasil query
        $data = $data->get();

        // dd($data);

        $this->totalBiaya = $data->sum('total_biaya');


        return view('livewire.dashboard.reporting.transaction', compact('data'));
    }

    public function edit($id)
    {
        $this->selectedId = $id;
        $this->isEdit = true;

        $this->dispatchBrowserEvent('reInitTwElement');
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

        $currencyString = preg_replace("/[^0-9]/", "", $this->biaya);
        $validateData['biaya'] = $currencyString;

        $transaction = ModelsTransaction::findOrFail($this->selectedId);
        $transaction->order_transaction = $this->order_transaction;
        $transaction->service = $this->service;
        $transaction->biaya = $validateData['biaya'];
        $transaction->payment_method = $this->payment_method;
        $transaction->technical_id = $validateData['technical_id'] === '' ? null : $validateData['technical_id'];
        $transaction->created_at = $order_date . ' ' . $time;

        $validateData['modal'] = 0;

        if ($validateData['product_id'] != $transaction->product_id) {
            $currentProduct = Product::findOrFail($transaction->product_id);
            $currentProduct->stok = $currentProduct->stok + 1;
            $currentProduct->save();

            $newProduct = Product::findOrFail($validateData['product_id']);
            $newProduct->stok = $newProduct->stok - 1;
            $newProduct->save();

            $transaction->product_id = $validateData['product_id'] === '' ? null : $validateData['product_id'];
        }

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

    public function delete($id)
    {
        $transaction = ModelsTransaction::findOrFail($id);

        if ($transaction->product_id !== null) {
            $product = Product::findOrFail($transaction->product_id);
            $product->stok = $product->stok + 1;
            $product->save();
        }

        $transactionItems = TransactionItem::where('transaction_id', $id)->get();
        foreach ($transactionItems as $item) {
            if ($item->product_id !== null) {
                $product_item = Product::findOrFail($item->product_id);
                $product_item->stok = $product_item->stok + 1;
                $product_item->save();
            }

            $item->delete();
        }


        $transaction->delete();

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Success',
            'text' => 'Successfully delete transaction',
            'icon' => 'success'
        ]);
    }

    public function complaint($id)
    {
        $data = ModelsTransaction::findOrFail($id);
        $data->status = 'complaint';
        $data->save();

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Success',
            'text' => 'Successfully complaint transaction',
            'icon' => 'success'
        ]);
    }

    public function handlePrintNota($id)
    {
        $transactions = DB::table('transactions')
            ->leftJoin('transaction_items', function ($join) {
                $join->on('transactions.id', '=', 'transaction_items.transaction_id')
                    ->whereNull('transaction_items.deleted_at');
            })
            ->leftJoin('customers', 'transactions.customer_id', '=', 'customers.id')
            ->select(
                'transactions.id as transaction_id',
                'transactions.biaya as transaction_biaya',
                'transactions.created_at as transaction_created_at',
                'transactions.created_by as transaction_created_by',
                'transactions.customer_id as transaction_customer_id',
                'transactions.deleted_at as transaction_deleted_at',
                'transactions.fee_teknisi as transaction_fee_teknisi',
                'transactions.modal as transaction_modal',
                'transactions.order_transaction as transaction_order_transaction',
                'transactions.payment_method as transaction_payment_method',
                'transactions.product_id as transaction_product_id',
                'transactions.service as transaction_service',
                'transactions.status as transaction_status',
                'transactions.technical_id as transaction_technical_id',
                'transactions.warranty as transaction_warranty',
                'transactions.warranty_type as transaction_warranty_type',
                'transactions.untung as transaction_untung',
                'transactions.updated_at as transaction_updated_at',
                'customers.name as customer_name',
                'customers.no_telp as customer_no_telp',
                'transaction_items.id as item_id',
                'transaction_items.biaya as item_biaya',
                'transaction_items.created_at as item_created_at',
                'transaction_items.deleted_at as item_deleted_at',
                'transaction_items.fee_teknisi as item_fee_teknisi',
                'transaction_items.modal as item_modal',
                'transaction_items.product_id as item_product_id',
                'transaction_items.service as item_service',
                'transaction_items.technical_id as item_technical_id',
                'transaction_items.untung as item_untung',
                'transaction_items.warranty as item_warranty',
                'transaction_items.warranty_type as item_warranty_type',
                'transaction_items.updated_at as item_updated_at'
            )
            ->where('transactions.id', $id)
            ->get()
            ->groupBy('transaction_id');

        $results = $transactions->map(function ($items, $transactionId) {
            $transaction = $items->first();

            $total = $transaction->transaction_biaya;

            return [
                'id' => $transaction->transaction_id,
                'created_at' => $transaction->transaction_created_at,
                'order_transaction' => $transaction->transaction_order_transaction,
                'status' => $transaction->transaction_status,
                'payment_method' => $transaction->transaction_payment_method,
                'customer_name' => $transaction->customer_name,
                'no_telp' => $transaction->customer_no_telp,
                'biaya' => $transaction->transaction_biaya,
                'created_at' => $transaction->transaction_created_at,
                'fee_teknisi' => $transaction->transaction_fee_teknisi,
                'modal' => $transaction->transaction_modal,
                'product_id' => $transaction->transaction_product_id,
                'service' => $transaction->transaction_service,
                'technical_id' => $transaction->transaction_technical_id,
                'untung' => $transaction->transaction_untung,
                'warranty' => $transaction->transaction_warranty,
                'warranty_type' => $transaction->transaction_warranty_type,
                'items' =>  $transaction->item_biaya !== null ? $items->map(function ($item, $index) use (&$total) {

                    $total += $item->item_biaya;

                    return [
                        'biaya' => $item->item_biaya,
                        'created_at' => $item->item_created_at,
                        'fee_teknisi' => $item->item_fee_teknisi,
                        'modal' => $item->item_modal,
                        'product_id' => $item->item_product_id,
                        'service' => $item->item_service,
                        'technical_id' => $item->item_technical_id,
                        'untung' => $item->item_untung,
                        'warranty' => $item->item_warranty,
                        'warranty_type' => $item->item_warranty_type
                    ];
                })->toArray() : [],
                'total' => $total
            ];
        });

        $detailItem = $results->values()->toArray()[0];

        $pdf = Pdf::loadView('pdf.receipt', ['detailItem' => $detailItem, 'date' => Carbon::now()->format('d/m/Y'), 'payment_method' => $detailItem['payment_method']])->setPaper([595.28, 420.945], 'landscape');
        $pdfPath = 'public/nota/receipt.pdf';
        Storage::put($pdfPath, $pdf->output());

        $pdfUrl = Storage::url($pdfPath);

        $this->dispatchBrowserEvent('printEvent', ['pdfUrl' => $pdfUrl]);
    }
}
