<?php

namespace App\Http\Livewire\Dashboard;

use App\Http\Controllers\ProductController;
use App\Models\LogActivityProduct;
use App\Models\Product;
use App\Models\PendingChange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Inventory extends Component
{
    public $searchTerm;
    public $isOpen;
    public $name;
    public $harga;
    public $harga_jual;
    public $stok;
    public $productId;
    public $reason = '';
    public $showReasonModal = false;
    public $pendingAction = null;
    public $pendingActionData = null;
    public $readyToLoad = false;
    public $products = [];
    public $hasMorePages = true;
    public $page = 1;
    public $perPage = 10;

    public $action = 'add';

    protected $rules = [
        'name' => 'required',
        'harga' => 'required',
        'harga_jual' => 'required',
        'stok' => 'required'
    ];

    protected $messages = [
        'name.required' => 'Nama produk harus diisi',
        'harga.required' => 'Harga produk harus diisi',
        'harga_jual.required' => 'Harga jual produk harus diisi',
        'stok.required' => 'Stok produk harus diisi'
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    
    public function updatingSearchTerm()
    {
        $this->products = [];
        $this->page = 1;
        $this->hasMorePages = true;
    }

    public function resetField()
    {
        $this->reset(['name', 'harga', 'harga_jual', 'stok', 'productId', 'action', 'isOpen']);
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
        $currencyStringJual = preg_replace("/[^0-9]/", "", $this->harga_jual);

        $validateData["harga"] = (int)$currencyString;
        $validateData["harga_jual"] = (int)$currencyStringJual;
        $validateData["stok"] = (int)$this->stok;
        $validateData["kode"] = time(); // Generate kode using timestamp

        // Check if user is sysadmin (operator) - needs verification
        if (Auth::user()->role === 'sysadmin') {
            // Create pending change instead of direct create
            PendingChange::create([
                'changeable_type' => Product::class,
                'changeable_id' => null,
                'action' => 'create',
                'old_data' => null,
                'new_data' => $validateData,
                'requested_by' => Auth::user()->id,
                'requested_at' => now(),
            ]);

            $this->dispatchBrowserEvent('swal', [
                'title' => 'Success',
                'text' => "Perubahan berhasil disimpan dan menunggu verifikasi.",
                'icon' => 'info'
            ]);
        } else {
            // Master admin can create directly
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
        }

        $this->resetField();
        
        // Refresh the products list after creating
        if ($this->readyToLoad) {
            $this->products = [];
            $this->page = 1;
            $this->hasMorePages = true;
            $this->loadMore();
        }
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->productId = $id;
        $this->name = $product->name;
        $this->harga = $product->harga;
        $this->harga_jual = $product->harga_jual;
        $this->stok = $product->stok;

        $this->action = 'edit';
        $this->isOpen = true;
    }

    public function update()
    {
        $validateData = $this->validate();
        $currencyString = preg_replace("/[^0-9]/", "", $this->harga);
        $currencyStringJual = preg_replace("/[^0-9]/", "", $this->harga_jual);

        $validateData["harga"] = (int)$currencyString;
        $validateData["harga_jual"] = (int)$currencyStringJual;
        $validateData["stok"] = (int)$this->stok;

        $product = Product::findOrFail($this->productId);

        // Check if user is sysadmin (operator) - needs verification
        if (Auth::user()->role === 'sysadmin') {
            // Store data and show reason modal
            $this->pendingAction = 'update';
            $this->pendingActionData = [
                'validateData' => $validateData,
                'product' => $product->toArray()
            ];
            $this->showReasonModal = true;
            $this->isOpen = false;
            return; // Return early to prevent resetField() from being called
        } else {
            // Master admin can update directly
            if ($this->name !== $product->name || $this->harga !== $product->harga || $this->harga_jual !== $product->harga_jual || $this->stok !== $product->stok) {
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

                if ((int)$this->stok !== $product->stok) {
                    $log->new_stok = (int)$this->stok;
                    $log->old_stok = $product->stok;
                }

                $log->save();
            }

            $request = new Request();
            $request->merge($validateData);

            $res = app(ProductController::class)->upadteProduct($request, $this->productId);

            $this->dispatchBrowserEvent('swal', [
                'title' => 'Success',
                'text' => $res->getData(true)['message'],
                'icon' => 'success'
            ]);
        }

        $this->resetField();
        
        // Refresh the products list after updating
        if ($this->readyToLoad) {
            $this->products = [];
            $this->page = 1;
            $this->hasMorePages = true;
            $this->loadMore();
        }
    }

    public function loadProducts()
    {
        $this->readyToLoad = true;
        $this->loadMore();
    }

    public function loadMore()
    {
        if (!$this->hasMorePages) {
            return;
        }

        $query = Product::where(function ($sub_query) {
            $sub_query->where('name', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('kode', 'like', '%' . $this->searchTerm . '%');
        })
        ->orderby('name', 'ASC');

        $newProducts = $query->skip(($this->page - 1) * $this->perPage)
            ->take($this->perPage)
            ->get();

        if ($newProducts->count() < $this->perPage) {
            $this->hasMorePages = false;
        }

        $this->products = array_merge($this->products, $newProducts->toArray());
        $this->page++;
    }

    public function render()
    {
        return view('livewire.dashboard.inventory', [
            'data' => collect($this->products)
        ])->layout('components.layouts.dashboard');
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);

        // Check if user is sysadmin (operator) - needs verification
        if (Auth::user()->role === 'sysadmin') {
            // Store data and show reason modal
            $this->pendingAction = 'delete';
            $this->pendingActionData = [
                'id' => $id,
                'product' => $product->toArray()
            ];
            $this->showReasonModal = true;
        } else {
            // Master admin can delete directly
            $log = new LogActivityProduct();
            $log->user = Auth::user()->name;
            $log->activity = 'delete';
            $log->product = $product->name;

            $response = app(ProductController::class)->deleteProduct($id);
            $status = $response->getData(true)['status'];
            $message = $response->getData(true)['message'];

            $log->save();

            $this->dispatchBrowserEvent('swal', [
                'title' => $status,
                'text' => $message,
                'icon' => $status
            ]);
            
            // Refresh the products list after deleting
            if ($this->readyToLoad) {
                $this->products = [];
                $this->page = 1;
                $this->hasMorePages = true;
                $this->loadMore();
            }
        }
    }

    public function submitReason()
    {
        $this->validate([
            'reason' => 'required|min:5'
        ], [
            'reason.required' => 'Alasan harus diisi',
            'reason.min' => 'Alasan minimal 5 karakter'
        ]);

        if ($this->pendingAction === 'update') {
            $validateData = $this->pendingActionData['validateData'];
            $product = $this->pendingActionData['product'];

            // Create pending change with reason
            PendingChange::create([
                'changeable_type' => Product::class,
                'changeable_id' => $this->productId,
                'action' => 'update',
                'old_data' => $product,
                'new_data' => array_merge($product, $validateData),
                'reason' => $this->reason,
                'requested_by' => Auth::user()->id,
                'requested_at' => now(),
            ]);

            $this->dispatchBrowserEvent('swal', [
                'title' => 'Success',
                'text' => "Perubahan berhasil disimpan dan menunggu verifikasi.",
                'icon' => 'info'
            ]);
        } elseif ($this->pendingAction === 'delete') {
            $id = $this->pendingActionData['id'];
            $product = $this->pendingActionData['product'];

            // Create pending change with reason
            PendingChange::create([
                'changeable_type' => Product::class,
                'changeable_id' => $id,
                'action' => 'delete',
                'old_data' => $product,
                'new_data' => null,
                'reason' => $this->reason,
                'requested_by' => Auth::user()->id,
                'requested_at' => now(),
            ]);

            $this->dispatchBrowserEvent('swal', [
                'title' => 'Success',
                'text' => "Permintaan penghapusan berhasil disimpan dan menunggu verifikasi.",
                'icon' => 'info'
            ]);
        }

        $this->closeReasonModal();
        $this->resetField();
        
        // Refresh the products list after submitting
        if ($this->readyToLoad) {
            $this->products = [];
            $this->page = 1;
            $this->hasMorePages = true;
            $this->loadMore();
        }
    }

    public function closeReasonModal()
    {
        $this->showReasonModal = false;
        $this->reason = '';
        $this->pendingAction = null;
        $this->pendingActionData = null;
    }
}
