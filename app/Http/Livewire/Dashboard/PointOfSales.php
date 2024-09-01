<?php

namespace App\Http\Livewire\Dashboard;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Technician;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PointOfSales extends Component
{
    public $biaya = '';
    public $service = '';
    public $product_id = '';
    public $technical_id = '';
    public $order_transaction = '';
    public $warranty = '';
    public $warranty_type = 'daily';

    public $inventory;
    public $technician;
    public $customers;
    public $customer_id;

    public $serviceItems = [];

    public $isOpen = false;
    public $name;
    public $no_telp;
    public $alamat;

    protected $rules = [
        'biaya' => 'required',
        'service' => 'required',
        'product_id' => '',
        'technical_id' => '',
        'order_transaction' => '',
        'warranty' => '',
        'warranty_type' => ''
    ];

    protected $messages = [
        'biaya.required' => 'This field is required',
        'service.required' => 'This field is required',
    ];

    protected $listeners = ['refreshComponent' => '$refresh', 'setSelected'];

    public function setSelected($value, $name)
    {
        $this->$name = $value;
    }

    public function resetFieldCustomer()
    {
        $this->reset(['name', 'no_telp', 'alamat']);
    }

    public function openModal()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function create()
    {
        $this->resetFieldCustomer();
        $this->openModal();
    }

    public function storeNewCustomer()
    {
        $this->validate([
            'name' => 'required',
            'no_telp' => 'required',
        ], [
            'name.required' => 'This field is required.',
            'no_telp.required' => 'This field is required.',
        ]);

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

        $this->resetFieldCustomer();
        $customers = Customer::select(DB::raw("CONCAT(name, ' - ', no_telp) as label"), DB::raw("id as value"))->get()->toArray();
        $this->emit('updateList', 'customer_id', $customers);
    }

    public function mount()
    {
        $this->inventory = Product::select(DB::raw("name as label"), DB::raw("id as value"))->get()->toArray();
        $this->technician = Technician::select(DB::raw("name as label"), DB::raw("id as value"))->get()->toArray();
        $this->customers = Customer::select(DB::raw("CONCAT(name, ' - ', no_telp) as label"), DB::raw("id as value"))->get()->toArray();
        $this->order_transaction = Transaction::generateOrderId();
    }

    public function render()
    {
        return view('livewire.dashboard.point-of-sales')
            ->layout('components.layouts.dashboard');
    }

    public function resetValue()
    {
        $this->biaya = '';
        $this->service = '';
        $this->product_id = '';
        $this->technical_id = '';
        $this->warranty = '';

        $this->dispatchBrowserEvent('refreshSelect', ['product_id', 'technical_id']);
    }

    public function resetAll()
    {
        $this->biaya = '';
        $this->service = '';
        $this->product_id = '';
        $this->technical_id = '';
        $this->customer_id = null;
        $this->serviceItems = [];

        $this->dispatchBrowserEvent('refreshSelect');
    }

    public function findProductById($id)
    {
        $product = Product::find($id);
        return $product;
    }

    public function findTechnicianById($id)
    {
        $technician = Technician::findOrFail($id);
        return $technician;
    }

    public function addServiceItems()
    {
        $this->validate();

        $product_name = '';
        $technical_name = '';
        $warranty = '';
        $warranty_type = null;

        if ($this->product_id === '') {
            $this->product_id = null;
        }

        if ($this->product_id !== null) {
            $product_name = $this->findProductById($this->product_id)->name;
        }

        if ($this->technical_id !== '') {
            $technical_name = $this->findTechnicianById($this->technical_id)->name;
            $product_name = '';
        }

        if ($this->warranty != 0 || $this->warranty != '') {
            $warranty = $this->warranty;
            $warranty_type = $this->warranty_type;
        }

        $this->serviceItems[] = [
            'biaya' => preg_replace("/[^0-9]/", "", $this->biaya),
            'service' => $this->service,
            'product_id' => $this->product_id,
            'product_name' => $product_name,
            'technical_id' => $this->technical_id,
            'technical_name' => $technical_name,
            'order_transaction' => $this->order_transaction,
            'warranty' => $warranty,
            'warranty_type' => $warranty_type,
        ];

        $this->resetValue();
    }

    public function removeServiceItem($index)
    {
        if (isset($this->serviceItems[$index])) {
            unset($this->serviceItems[$index]);
        }
    }

    public function submit()
    {
        if ($this->customer_id === null) {
            return $this->dispatchBrowserEvent('swal', [
                'title' => 'Error',
                'text' => 'Empty Customer Selected',
                'icon' => 'error'
            ]);
        }

        if (count($this->serviceItems) === 0) {
            return $this->dispatchBrowserEvent('swal', [
                'title' => 'Error',
                'text' => 'Empty Service items',
                'icon' => 'error'
            ]);
        }
        $request = new Request();
        $request->merge($this->serviceItems);

        $response = app(TransactionController::class)->postTransaction($request, $this->customer_id);

        $status = $response->getData(true)['status'];
        $message = $response->getData(true)['message'];

        $this->dispatchBrowserEvent('swal', [
            'title' => $status,
            'text' => $message,
            'icon' => $status
        ]);

        $this->resetAll();
        $this->order_transaction = Transaction::generateOrderId();

        // return redirect()->route('dashboard.point-of-sales');
    }
}
