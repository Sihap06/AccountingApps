<?php

namespace App\Http\Livewire\Dashboard;

use App\Http\Controllers\ProductController;
use App\Models\LogActivityProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Inventory extends Component
{
    use WithPagination;

    public $searchTerm;
    public $isOpen;
    public $name;
    public $harga;
    public $stok;
    public $add_stok;
    public $productId;

    public $action = 'add';

    protected $rules = [
        'name' => 'required',
        'harga' => 'required',
        'stok' => 'required',
        'add_stok' => ''
    ];

    protected $messages = [
        'name.required' => 'Nama produk harus diisi',
        'harga.required' => 'Harga produk harus diisi',
        'stok.required' => 'Stok produk harus diisi'
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function resetField()
    {
        $this->reset();
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function store()
    {
        $validateData = $this->validate();
        $currencyString = preg_replace("/[^0-9]/", "", $this->harga);

        $validateData["harga"] = (int)$currencyString;

        $request = new Request();
        $request->merge($validateData);


        $response = app(ProductController::class)->postProduct($request);
        $status = $response->getData(true)['status'];
        $message = $response->getData(true)['message'];

        $log = new LogActivityProduct();
        $log->user = Auth::user()->name;
        $log->activity = 'store';
        $log->product = $this->name;
        $log->new_name = $this->name;
        $log->new_price = $this->harga;
        $log->new_stok = $this->stok;
        $log->save();

        $this->dispatchBrowserEvent('swal', [
            'title' => $status,
            'text' => $message,
            'icon' => $status
        ]);

        $this->resetField();
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->productId = $id;
        $this->name = $product->name;
        $this->harga = $product->harga;
        $this->stok = $product->stok;

        $this->action = 'edit';
        $this->isOpen = true;
    }

    public function update()
    {
        $validateData = $this->validate();
        $currencyString = preg_replace("/[^0-9]/", "", $this->harga);

        $validateData["harga"] = (int)$currencyString;
        $validateData["stok"] = (int)$this->stok + (int)$this->add_stok;

        $product = Product::findOrFail($this->productId);

        $log = new LogActivityProduct();
        $log->user = Auth::user()->name;
        $log->activity = 'update';
        $log->product = $product->name;

        if ($product->name !== $this->name) {
            $log->new_name = $this->name;
            $log->old_name = $product->name;
        }

        if ($product->harga !== $this->harga) {
            $log->new_price = $this->harga;
            $log->old_price = $product->harga;
        }

        if ((int)$this->add_stok !== 0) {
            $log->new_stok = (int)$this->stok + (int)$this->add_stok;
            $log->old_stok = $product->stok;
        }

        $request = new Request();
        $request->merge($validateData);

        $res = app(ProductController::class)->upadteProduct($request, $this->productId);

        $log->save();

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Success',
            'text' => $res->getData(true)['message'],
            'icon' => 'success'
        ]);

        $this->resetField();
    }

    public function render()
    {
        $data = Product::where(function ($sub_query) {
            $sub_query->where('name', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('kode', 'like', '%' . $this->searchTerm . '%');
        })
            ->orderby('name', 'ASC')
            ->paginate(10);
        return view('livewire.dashboard.inventory', compact('data'))
            ->layout('components.layouts.dashboard');
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id)->name;

        $log = new LogActivityProduct();
        $log->user = Auth::user()->name;
        $log->activity = 'delete';
        $log->product = $product;

        $response = app(ProductController::class)->deleteProduct($id);
        $status = $response->getData(true)['status'];
        $message = $response->getData(true)['message'];

        $log->save();

        $this->dispatchBrowserEvent('swal', [
            'title' => $status,
            'text' => $message,
            'icon' => $status
        ]);
    }
}
