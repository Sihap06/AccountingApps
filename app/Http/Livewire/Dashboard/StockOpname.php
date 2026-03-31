<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Product;
use App\Models\StockOpname as StockOpnameModel;
use App\Models\StockOpnameItem;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class StockOpname extends Component
{
    use WithPagination;

    // Trigger modal (owner)
    public $showTriggerModal = false;
    public $triggerNotes = '';
    public $selectedKasir = '';

    // Stock opname process (kasir)
    public $activeOpname = null;
    public $searchProduct = '';
    public $filterStatus = 'all'; // all, checked, unchecked

    // Detail/history
    public $showDetailModal = false;
    public $detailOpname = null;
    public $detailItems = [];

    // Item input
    public $editingItemId = null;
    public $actualStock = null;
    public $itemNotes = '';

    public function mount()
    {
        // Owner selalu bisa akses, kasir/manajer bisa akses jika punya permission ATAU ada stok opname aktif yang ditugaskan
        $user = auth()->user();
        if (!$user->isOwner() && !$user->hasPermission('stock_opname')) {
            $hasActiveOpname = \App\Models\StockOpname::active()
                ->where(function ($q) use ($user) {
                    $q->where('assigned_to', $user->id)
                      ->orWhereNull('assigned_to');
                })
                ->exists();

            if (!$hasActiveOpname) {
                abort(403, 'Unauthorized access');
            }
        }

        $this->loadActiveOpname();
    }

    public function loadActiveOpname()
    {
        // Load active stock opname for current user (kasir) or any active (owner)
        if (auth()->user()->isOwner()) {
            $this->activeOpname = StockOpnameModel::active()
                ->with(['triggeredBy', 'assignedTo', 'items'])
                ->latest()
                ->first();
        } else {
            $this->activeOpname = StockOpnameModel::active()
                ->where(function ($q) {
                    $q->where('assigned_to', auth()->id())
                      ->orWhereNull('assigned_to');
                })
                ->with(['triggeredBy', 'assignedTo', 'items'])
                ->latest()
                ->first();
        }
    }

    // === Owner: Trigger Stock Opname ===

    public function openTriggerModal()
    {
        if (!auth()->user()->isOwner()) return;

        // Check if there's already an active opname
        $existing = StockOpnameModel::active()->first();
        if ($existing) {
            $this->dispatchBrowserEvent('swal', [
                'title' => 'Tidak Bisa Membuat',
                'text' => 'Masih ada stok opname yang aktif. Selesaikan terlebih dahulu.',
                'icon' => 'warning'
            ]);
            return;
        }

        $this->showTriggerModal = true;
        $this->triggerNotes = '';
        $this->selectedKasir = '';
    }

    public function closeTriggerModal()
    {
        $this->showTriggerModal = false;
        $this->resetValidation();
    }

    public function triggerStockOpname()
    {
        if (!auth()->user()->isOwner()) return;

        $this->validate([
            'selectedKasir' => 'nullable|exists:users,id',
        ]);

        $products = Product::all();

        if ($products->isEmpty()) {
            $this->dispatchBrowserEvent('swal', [
                'title' => 'Tidak Ada Produk',
                'text' => 'Tidak ada produk untuk dilakukan stok opname.',
                'icon' => 'warning'
            ]);
            return;
        }

        $opname = StockOpnameModel::create([
            'triggered_by' => auth()->id(),
            'assigned_to' => $this->selectedKasir ?: null,
            'status' => 'pending',
            'notes' => $this->triggerNotes ?: null,
        ]);

        // Create items for each product
        foreach ($products as $product) {
            StockOpnameItem::create([
                'stock_opname_id' => $opname->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'system_stock' => $product->stok,
            ]);
        }

        $this->closeTriggerModal();
        $this->loadActiveOpname();

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Stok Opname Dibuat',
            'text' => 'Notifikasi telah dikirim ke kasir untuk melakukan pengecekan stok.',
            'icon' => 'success'
        ]);
    }

    // === Kasir: Start & Process Stock Opname ===

    public function startOpname()
    {
        if (!$this->activeOpname) return;

        $this->activeOpname->update(['status' => 'in_progress']);
        $this->loadActiveOpname();
    }

    public function editItem($itemId)
    {
        $item = StockOpnameItem::find($itemId);
        if (!$item) return;

        $this->editingItemId = $itemId;
        $this->actualStock = $item->actual_stock;
        $this->itemNotes = $item->notes ?? '';
    }

    public function cancelEdit()
    {
        $this->editingItemId = null;
        $this->actualStock = null;
        $this->itemNotes = '';
    }

    public function saveItem()
    {
        $this->validate([
            'actualStock' => 'required|integer|min:0',
        ], [
            'actualStock.required' => 'Stok aktual wajib diisi.',
            'actualStock.integer' => 'Stok aktual harus berupa angka.',
            'actualStock.min' => 'Stok aktual tidak boleh negatif.',
        ]);

        $item = StockOpnameItem::find($this->editingItemId);
        if (!$item) return;

        $item->update([
            'actual_stock' => $this->actualStock,
            'difference' => $this->actualStock - $item->system_stock,
            'notes' => $this->itemNotes ?: null,
            'checked' => true,
        ]);

        $this->cancelEdit();
        $this->loadActiveOpname();
    }

    public function completeOpname()
    {
        if (!$this->activeOpname) return;

        // Check if all items are checked
        $unchecked = $this->activeOpname->items()->where('checked', false)->count();
        if ($unchecked > 0) {
            $this->dispatchBrowserEvent('swal', [
                'title' => 'Belum Lengkap',
                'text' => "Masih ada {$unchecked} produk yang belum dicek.",
                'icon' => 'warning'
            ]);
            return;
        }

        $this->activeOpname->update([
            'status' => 'completed',
            'completed_by' => auth()->id(),
            'completed_at' => now(),
        ]);

        $this->activeOpname = null;

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Stok Opname Selesai',
            'text' => 'Stok opname telah selesai dilakukan.',
            'icon' => 'success'
        ]);
    }

    public function cancelOpname()
    {
        if (!$this->activeOpname || !auth()->user()->isOwner()) return;

        $this->activeOpname->update(['status' => 'cancelled']);
        $this->activeOpname = null;

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Stok Opname Dibatalkan',
            'text' => 'Stok opname telah dibatalkan.',
            'icon' => 'info'
        ]);
    }

    // === Detail History ===

    public function showDetail($opnameId)
    {
        $this->detailOpname = StockOpnameModel::with(['triggeredBy', 'completedBy', 'assignedTo', 'items.product'])->find($opnameId);
        $this->detailItems = $this->detailOpname ? $this->detailOpname->items->toArray() : [];
        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->detailOpname = null;
        $this->detailItems = [];
    }

    public function render()
    {
        $kasirList = User::whereHas('role', function ($query) {
            $query->where('name', 'Kasir');
        })->get();

        // Get items for active opname with filtering
        $activeItems = collect();
        if ($this->activeOpname) {
            $query = StockOpnameItem::where('stock_opname_id', $this->activeOpname->id);

            if ($this->searchProduct) {
                $query->where('product_name', 'like', '%' . $this->searchProduct . '%');
            }

            if ($this->filterStatus === 'checked') {
                $query->where('checked', true);
            } elseif ($this->filterStatus === 'unchecked') {
                $query->where('checked', false);
            }

            $activeItems = $query->orderBy('checked')->orderBy('product_name')->get();
        }

        // History
        $history = StockOpnameModel::with(['triggeredBy', 'completedBy'])
            ->whereIn('status', ['completed', 'cancelled'])
            ->latest()
            ->paginate(10);

        return view('livewire.dashboard.stock-opname', compact('kasirList', 'activeItems', 'history'))
            ->layout('components.layouts.dashboard');
    }
}
