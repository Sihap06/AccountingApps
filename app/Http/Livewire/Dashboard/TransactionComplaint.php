<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\ProductReturn;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionComplaintExport;
use Carbon\Carbon;

class TransactionComplaint extends Component
{
    use WithPagination;
    public $searchTerm;
    public $detailItem;
    public $isOpen = false;
    public $is_dashboard;


    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount($is_dashboard = false)
    {
        $this->is_dashboard = $is_dashboard;
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function detail($id)
    {
        $transactions = DB::table('transactions')
            ->leftJoin('transaction_items', 'transactions.id', '=', 'transaction_items.transaction_id')
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
                'transactions.untung as transaction_untung',
                'transactions.updated_at as transaction_updated_at',
                'transactions.phone_brand as transaction_phone_brand',
                'transactions.phone_type as transaction_phone_type',
                'transactions.phone_color as transaction_phone_color',
                'transactions.phone_imei as transaction_phone_imei',
                'transactions.phone_internal as transaction_phone_internal',
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
                'transaction_items.updated_at as item_updated_at',
                'transaction_items.phone_brand as item_phone_brand',
                'transaction_items.phone_type as item_phone_type',
                'transaction_items.phone_color as item_phone_color',
                'transaction_items.phone_imei as item_phone_imei',
                'transaction_items.phone_internal as item_phone_internal'
            )
            ->where('transactions.id', $id)
            ->get()
            ->groupBy('transaction_id');

        $results = $transactions->map(function ($items, $transactionId) {
            $transaction = $items->first();

            $total = 0;

            return [
                'id' => $transaction->transaction_id,
                'created_at' => $transaction->transaction_created_at,
                'order_transaction' => $transaction->transaction_order_transaction,
                'status' => $transaction->transaction_status,
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
                'phone_brand' => $transaction->transaction_phone_brand,
                'phone_type' => $transaction->transaction_phone_type,
                'phone_color' => $transaction->transaction_phone_color,
                'phone_imei' => $transaction->transaction_phone_imei,
                'phone_internal' => $transaction->transaction_phone_internal,
                'items' =>  $transaction->item_biaya !== null ? $items->map(function ($item, $index) use (&$total) {

                    if ($index === 0) {
                        $total += $item->transaction_biaya + $item->item_biaya;
                    } else {
                        $total += $item->item_biaya;
                    }

                    return [
                        'biaya' => $item->item_biaya,
                        'created_at' => $item->item_created_at,
                        'fee_teknisi' => $item->item_fee_teknisi,
                        'modal' => $item->item_modal,
                        'product_id' => $item->item_product_id,
                        'service' => $item->item_service,
                        'technical_id' => $item->item_technical_id,
                        'untung' => $item->item_untung,
                        'phone_brand' => $item->item_phone_brand,
                        'phone_type' => $item->item_phone_type,
                        'phone_color' => $item->item_phone_color,
                        'phone_imei' => $item->item_phone_imei,
                        'phone_internal' => $item->item_phone_internal,
                    ];
                })->toArray() : [],
                'total' => $total
            ];
        });
        // dd($results->values()->toArray());


        $this->detailItem = count($results->values()->toArray()) > 0 ? $results->values()->toArray()[0] : [];
        $this->openModal();
    }

    public function render()
    {

        $data = Transaction::leftJoin('transaction_items', function ($join) {
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
                'transactions.status'
            )
            ->where('transactions.status', 'complaint')
            ->whereNull('transactions.deleted_at')
            ->where(function ($sub_query) {
                $sub_query->where('order_transaction', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('customers.name', 'like', '%' . $this->searchTerm . '%');
            })
            ->groupBy(
                'transactions.created_at',
                'customers.name',
                'transactions.id',
                'transactions.order_transaction',
                'transactions.biaya',
                'transactions.modal',
                'transactions.payment_method'
            )->get();

        return view('livewire.dashboard.transaction-complaint', compact('data'));
    }

    public function TransactionComplete($id)
    {
        $data = Transaction::findOrFail($id);
        $data->status = 'done';
        $data->save();

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Transaction Done',
            'text' => '',
            'icon' => 'success'
        ]);
    }

    public function cancelComplaintTransaction($id)
    {
        $transaction = Transaction::findOrFail($id);
        
        // Move main transaction sparepart to return stock if used and product exists
        if ($transaction->product_id !== null) {
            $product = Product::find($transaction->product_id);
            if ($product) {
                ProductReturn::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $transaction->product_id,
                    'quantity' => 1,
                    'return_reason' => 'complaint_cancel',
                    'return_type' => 'transaction',
                    'returned_by' => Auth::id(),
                    'notes' => 'Returned due to complaint cancellation - Order: ' . $transaction->order_transaction
                ]);
            }
        }

        // Move transaction items spareparts to return stock
        $transactionItems = TransactionItem::where('transaction_id', $id)
            ->whereNotNull('product_id')
            ->get();
            
        foreach ($transactionItems as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                ProductReturn::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item->product_id,
                    'quantity' => 1,
                    'return_reason' => 'complaint_cancel',
                    'return_type' => 'transaction_item',
                    'transaction_item_id' => $item->id,
                    'returned_by' => Auth::id(),
                    'notes' => 'Returned due to complaint cancellation - Service: ' . $item->service
                ]);
            }
        }

        // Cancel the transaction
        $transaction->status = 'cancel';
        $transaction->save();

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Transaction Cancelled',
            'text' => 'Products have been moved to return stock',
            'icon' => 'success'
        ]);
    }

    public function exportExcel()
    {
        $data = Transaction::leftJoin('transaction_items', function ($join) {
            $join->on('transactions.id', '=', 'transaction_items.transaction_id')
                ->whereNull('transaction_items.deleted_at');
        })
            ->leftJoin('customers', 'customers.id', '=', 'transactions.customer_id')
            ->leftJoin('technicians', 'transactions.technical_id', '=', 'technicians.id')
            ->select(
                'transactions.id',
                'transactions.created_at',
                'customers.name as customer_name',
                'customers.no_telp as customer_phone',
                'transactions.order_transaction',
                'transactions.service',
                DB::raw('GROUP_CONCAT(transaction_items.service SEPARATOR ", ") as service_name'),
                DB::raw('transactions.biaya as first_item_biaya'),
                DB::raw('SUM(transaction_items.biaya) as other_items_biaya'),
                DB::raw('transactions.modal as first_item_modal'),
                DB::raw('SUM(transaction_items.modal) as other_items_modal'),
                DB::raw('transactions.biaya + IFNULL(SUM(transaction_items.biaya), 0) as total_biaya'),
                'transactions.payment_method',
                'transactions.status',
                'technicians.name as technician_name',
                'transactions.warranty',
                'transactions.warranty_type'
            )
            ->where('transactions.status', 'complaint')
            ->whereNull('transactions.deleted_at')
            ->groupBy(
                'transactions.created_at',
                'customers.name',
                'customers.no_telp',
                'transactions.id',
                'transactions.order_transaction',
                'transactions.service',
                'transactions.biaya',
                'transactions.modal',
                'transactions.payment_method',
                'transactions.status',
                'technicians.name',
                'transactions.warranty',
                'transactions.warranty_type'
            )->get();

        $export = new TransactionComplaintExport($data);
        
        return Excel::download($export, 'transaction-complaint-' . Carbon::now()->format('Y-m-d') . '.xlsx');
    }
}
