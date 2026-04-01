<?php

namespace App\Http\Livewire\Dashboard;

use App\Http\Controllers\ProductController;
use App\Models\LogActivityProduct;
use App\Models\Product;
use App\Models\ProductReturn;
use App\Models\PendingChange;
use App\Models\StockUpdate;
use App\Models\StockUpdateItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Inventory extends Component
{
    use WithFileUploads;

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
    
    public $showReturnModal = false;
    public $selectedProductId = null;
    public $productReturns = [];

    public $action = 'add';

    // Stock Update properties
    public $showStockUpdateModal = false;
    public $stockUpdateSearch = '';
    public $stockUpdateItems = []; // [{product_id, product_name, current_stock, qty_added}]
    public $stockUpdateNota = null;
    public $stockUpdateNotes = '';

    protected $rules = [
        'name' => 'required',
        'harga' => 'required',
        'harga_jual' => 'required',
        'stok' => 'required'
    ];

    protected $messages = [
        'name.required' => 'Product name is required',
        'harga.required' => 'Product capital price is required',
        'harga_jual.required' => 'Product selling price is required',
        'stok.required' => 'Product stock is required'
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
        $this->reset(['name', 'harga', 'harga_jual', 'stok', 'productId']);
        $this->resetValidation();
        $this->action = 'add';
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
        if (Auth::user()->requiresVerification()) {
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
                'text' => "Changes saved successfully and awaiting verification.",
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
        if (Auth::user()->requiresVerification()) {
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
        ->withSum('returns as return_stock', 'quantity')
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
        if (Auth::user()->requiresVerification()) {
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
            'reason.required' => 'Reason is required',
            'reason.min' => 'Reason must be at least 5 characters'
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
                'text' => "Changes saved successfully and awaiting verification.",
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
                'text' => "Delete request saved successfully and awaiting verification.",
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

    public function showReturns($productId)
    {
        $this->selectedProductId = $productId;
        $this->productReturns = ProductReturn::with(['transaction', 'returnedBy'])
            ->where('product_id', $productId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
        $this->showReturnModal = true;
    }

    public function restoreReturnToStock($returnId)
    {
        $return = ProductReturn::find($returnId);
        if (!$return) return;

        $product = Product::find($return->product_id);
        if (!$product) return;

        // Add return quantity back to product stock
        $product->bypassVerification = true;
        $product->stok = $product->stok + $return->quantity;
        $product->save();

        // Log activity
        $log = new LogActivityProduct();
        $log->user = Auth::user()->name;
        $log->activity = 'update';
        $log->product = $product->name;
        $log->old_stok = $product->stok - $return->quantity;
        $log->new_stok = $product->stok;
        $log->save();

        $return->delete();

        // Refresh return modal data
        $this->showReturns($this->selectedProductId);

        // Refresh products list
        if ($this->readyToLoad) {
            $this->products = [];
            $this->page = 1;
            $this->hasMorePages = true;
            $this->loadMore();
        }

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Stock Restored',
            'text' => "Product stock successfully increased by {$return->quantity} units.",
            'icon' => 'success'
        ]);
    }

    public function deleteReturn($returnId)
    {
        $return = ProductReturn::find($returnId);
        if (!$return) return;

        $return->delete();

        // Refresh return modal data
        $this->showReturns($this->selectedProductId);

        // Refresh products list
        if ($this->readyToLoad) {
            $this->products = [];
            $this->page = 1;
            $this->hasMorePages = true;
            $this->loadMore();
        }

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Return Deleted',
            'text' => 'Return data successfully deleted.',
            'icon' => 'success'
        ]);
    }

    public function closeReturnModal()
    {
        $this->showReturnModal = false;
        $this->selectedProductId = null;
        $this->productReturns = [];
    }

    // === Stock Update Methods ===

    public function openStockUpdateModal()
    {
        $this->showStockUpdateModal = true;
        $this->stockUpdateItems = [];
        $this->stockUpdateNota = null;
        $this->stockUpdateNotes = '';
        $this->stockUpdateSearch = '';
    }

    public function closeStockUpdateModal()
    {
        $this->showStockUpdateModal = false;
        $this->stockUpdateItems = [];
        $this->stockUpdateNota = null;
        $this->stockUpdateNotes = '';
        $this->stockUpdateSearch = '';
        $this->resetValidation();
    }

    public function addStockUpdateProduct($productId)
    {
        // Prevent duplicates
        foreach ($this->stockUpdateItems as $item) {
            if ($item['product_id'] == $productId) {
                return;
            }
        }

        $product = Product::find($productId);
        if (!$product) return;

        $this->stockUpdateItems[] = [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'current_stock' => $product->stok,
            'current_price' => $product->harga,
            'qty_added' => 1,
            'purchase_price' => '',
        ];

        $this->stockUpdateSearch = '';
    }

    public function removeStockUpdateProduct($index)
    {
        unset($this->stockUpdateItems[$index]);
        $this->stockUpdateItems = array_values($this->stockUpdateItems);
    }

    public function submitStockUpdate()
    {
        $rules = [
            'stockUpdateItems' => 'required|array|min:1',
            'stockUpdateItems.*.qty_added' => 'required|integer|min:1',
            'stockUpdateItems.*.purchase_price' => 'required',
            'stockUpdateNota' => 'required|image|max:5120',
        ];

        $messages = [
            'stockUpdateItems.required' => 'Select at least 1 product.',
            'stockUpdateItems.min' => 'Select at least 1 product.',
            'stockUpdateItems.*.qty_added.required' => 'Quantity is required.',
            'stockUpdateItems.*.qty_added.integer' => 'Quantity must be a number.',
            'stockUpdateItems.*.qty_added.min' => 'Minimum quantity is 1.',
            'stockUpdateItems.*.purchase_price.required' => 'Purchase price is required.',
            'stockUpdateNota.required' => 'Receipt upload is required.',
            'stockUpdateNota.image' => 'File must be an image.',
            'stockUpdateNota.max' => 'Max file size is 5MB.',
        ];

        $this->validate($rules, $messages);

        // Compress and store nota image
        $notaPath = $this->compressAndStoreNota($this->stockUpdateNota);

        // Create stock update record
        $stockUpdate = StockUpdate::create([
            'user_id' => Auth::id(),
            'nota_image' => $notaPath,
            'notes' => $this->stockUpdateNotes ?: null,
        ]);

        // Update each product stock and price
        foreach ($this->stockUpdateItems as $item) {
            $product = Product::find($item['product_id']);
            if (!$product) continue;

            $stockBefore = $product->stok;
            $priceBefore = $product->harga;
            $qtyAdded = (int) $item['qty_added'];
            $purchasePrice = (int) preg_replace("/[^0-9]/", "", $item['purchase_price']);

            // Weighted average: ((old_stock * old_price) + (qty_added * new_price)) / (old_stock + qty_added)
            $totalStock = $stockBefore + $qtyAdded;
            $newAvgPrice = $totalStock > 0
                ? (int) round((($stockBefore * $priceBefore) + ($qtyAdded * $purchasePrice)) / $totalStock)
                : $purchasePrice;

            // Bypass verification for stock updates
            $product->bypassVerification = true;
            $product->stok = $totalStock;
            $product->harga = $newAvgPrice;
            $product->save();

            StockUpdateItem::create([
                'stock_update_id' => $stockUpdate->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'qty_added' => $qtyAdded,
                'purchase_price' => $purchasePrice,
                'stock_before' => $stockBefore,
                'stock_after' => $totalStock,
                'price_before' => $priceBefore,
                'price_after' => $newAvgPrice,
            ]);

            // Log activity
            $log = new LogActivityProduct();
            $log->user = Auth::user()->name;
            $log->activity = 'stock_update';
            $log->product = $product->name;
            $log->old_stok = $stockBefore;
            $log->new_stok = $stockBefore + $qtyAdded;
            $log->save();
        }

        $this->closeStockUpdateModal();

        // Refresh products list
        if ($this->readyToLoad) {
            $this->products = [];
            $this->page = 1;
            $this->hasMorePages = true;
            $this->loadMore();
        }

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Stock Updated',
            'text' => 'Product stock successfully added.',
            'icon' => 'success'
        ]);
    }

    private function compressAndStoreNota($imageFile)
    {
        $extension = strtolower($imageFile->getClientOriginalExtension());
        $filename = time() . '_' . uniqid() . '.' . $extension;
        $fullPath = 'stock-update-nota/' . $filename;

        $path = $imageFile->storeAs('public/stock-update-nota', $filename);

        return $fullPath;
    }

    public function getStockUpdateSearchResultsProperty()
    {
        if (strlen($this->stockUpdateSearch) < 2) {
            return collect();
        }

        $existingIds = collect($this->stockUpdateItems)->pluck('product_id')->toArray();

        return Product::where(function ($q) {
            $q->where('name', 'like', '%' . $this->stockUpdateSearch . '%')
              ->orWhere('kode', 'like', '%' . $this->stockUpdateSearch . '%');
        })
        ->whereNotIn('id', $existingIds)
        ->limit(10)
        ->get();
    }
}
