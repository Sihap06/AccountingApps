<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomersExport;
use Carbon\Carbon;

class Customers extends Component
{
    use WithPagination;

    public $searchTerm;
    public $isOpen = false;
    public $isOpenDetailTransaction = false;
    public $modalType = 'store';
    public $customerId;
    public $name;
    public $no_telp;
    public $alamat;
    public $transactionItems = [];
    public $startDate;
    public $endDate;
    protected $data;

    protected $rules = [
        'name' => 'required|string|max:255',
        'no_telp' => 'required|string|max:15',
        'alamat' => 'max:255',
    ];

    protected $messages = [
        'name.required' => 'This field is required.',
        'no_telp.required' => 'This field is required.',
        'no_telp.max' => 'This field maximal 15 character'
    ];

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function resetField()
    {
        $this->reset(['name', 'no_telp', 'alamat']);
    }

    public function create()
    {
        $this->modalType = 'store';
        $this->resetField();
        $this->openModal();
    }

    public function store()
    {
        $this->validate();

        $checkIfExistCustomer = Customer::where('no_telp', $this->no_telp)->first();
        if ($checkIfExistCustomer) {
            return $this->dispatchBrowserEvent('swal', [
                'title' => 'customer has registered',
                'icon' => 'error'
            ]);
        }

        Customer::create([
            'name' => $this->name,
            'no_telp' => $this->no_telp,
            'alamat' => $this->alamat,
        ]);

        $this->closeModal();

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Customer Create',
            'text' => 'Customer successfully created.',
            'icon' => 'success'
        ]);

        $this->resetField();
        $this->resetPage();
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);

        $this->name = $customer->name;
        $this->no_telp = $customer->no_telp;
        $this->alamat = $customer->alamat;
        $this->customerId = $id;

        $this->modalType = 'update';
        $this->openModal();
    }

    public function update()
    {
        $this->validate();
        $customer = Customer::findOrFail($this->customerId);
        $customer->update([
            'name' => $this->name,
            'no_telp' => $this->no_telp,
            'alamat' => $this->alamat,
        ]);
        $this->closeModal();
        $this->dispatchBrowserEvent('swal', [
            'title' => 'Customer Update',
            'text' => 'Customer successfully updated.',
            'icon' => 'success'
        ]);

        $this->resetPage();
    }

    public function render()
    {
        $data = Customer::where(function ($sub_query) {
            $sub_query->where('name', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('no_telp', 'like', '%' . $this->searchTerm . '%');
        })
            ->orderby('created_at', 'DESC')
            ->paginate(10);
        return view('livewire.dashboard.customers', compact('data'))
            ->layout('components.layouts.dashboard');
    }

    public function delete($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        session()->flash('message', 'Customer successfully deleted.');

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Customer Delete',
            'text' => 'Customer successfully deleted.',
            'icon' => 'success'
        ]);

        $this->resetPage();
    }

    public function detailTransaction($id)
    {

        $transactions = DB::table('transactions')
            ->leftJoin('transaction_items', function ($join) {
                $join->on('transactions.id', '=', 'transaction_items.transaction_id')
                    ->whereNull('transaction_items.deleted_at');
            })
            ->join('customers', 'transactions.customer_id', '=', 'customers.id')
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
            ->where('customers.id', $id)
            ->where('transactions.status', 'done')
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

                    if ($index > 0) {
                        $total += $item->item_biaya;
                    }

                    return [
                        'id' => $item->item_id,
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

        $this->transactionItems = $results->values()->toArray();

        $this->isOpenDetailTransaction = true;
    }

    public function closeModalDetailTransaction()
    {
        $this->isOpenDetailTransaction = false;
        $this->transactionItems = [];
    }

    public function exportExcel()
    {
        // Check if user is master admin
        if (auth()->user()->role !== 'master_admin') {
            session()->flash('error', 'Hanya master admin yang dapat mengekspor data.');
            return;
        }

        // Set default dates if not provided
        $startDate = $this->startDate ? Carbon::parse($this->startDate)->startOfDay() : null;
        $endDate = $this->endDate ? Carbon::parse($this->endDate)->endOfDay() : null;

        $query = Customer::query();

        // Apply date filter if provided
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        // Get customer data - only name and phone
        $customers = $query->select('name', 'no_telp', 'created_at')
            ->orderBy('created_at', 'DESC')
            ->get();

        $export = new CustomersExport($customers, $startDate, $endDate);
        
        $filename = 'customers';
        if ($startDate && $endDate) {
            $filename .= '-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d');
        } else {
            $filename .= '-' . Carbon::now()->format('Y-m-d');
        }
        
        return Excel::download($export, $filename . '.xlsx');
    }
}
