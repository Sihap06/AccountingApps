<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TransactionCancel extends Component
{
    use WithPagination;
    public $searchTerm;
    public $detailItem;
    public $isOpen = false;
    public $year;
    public $month;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->year = Carbon::now()->format('Y');
        $this->month = Carbon::now()->format('m');
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
                'customers.name as customer_name',
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
                'transaction_items.updated_at as item_updated_at'
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
                'biaya' => $transaction->transaction_biaya,
                'created_at' => $transaction->transaction_created_at,
                'fee_teknisi' => $transaction->transaction_fee_teknisi,
                'modal' => $transaction->transaction_modal,
                'product_id' => $transaction->transaction_product_id,
                'service' => $transaction->transaction_service,
                'technical_id' => $transaction->transaction_technical_id,
                'untung' => $transaction->transaction_untung,
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
        // $data = Transaction::leftJoin('customers', 'transactions.customer_id', '=', 'customers.id')
        //     ->select('transactions.*', 'customers.name as customer_name')
        //     ->where('transactions.status', 'cancel')
        //     ->where(function ($sub_query) {
        //         $sub_query->where('order_transaction', 'like', '%' . $this->searchTerm . '%')
        //             ->orWhere('customers.name', 'like', '%' . $this->searchTerm . '%');
        //     })->get();

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
            ->where('transactions.status', 'cancel')
            ->whereNull('transactions.deleted_at');

        if ($this->searchTerm) {
            $data = $data->where(function ($sub_query) {
                $sub_query->where('order_transaction', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('customers.name', 'like', '%' . $this->searchTerm . '%');
            });
        } else {
            $data = $data->whereMonth('transactions.created_at', $this->month)
                ->whereYear('transactions.created_at', $this->year);
        }

        $data = $data->groupBy(
            'transactions.created_at',
            'customers.name',
            'transactions.id',
            'transactions.order_transaction',
            'transactions.biaya',
            'transactions.modal',
            'transactions.payment_method'
        )->get();

        return view('livewire.dashboard.transaction-cancel', compact('data'));
    }
}
