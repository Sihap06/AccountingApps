<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\StockUpdate;
use App\Exports\StockUpdateExport;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class StockUpdateHistory extends Component
{
    use WithPagination;

    public $showDetailModal = false;
    public $showNotaModal = false;
    public $detailUpdate = null;
    public $detailItems = [];
    public $notaImageUrl = '';
    public $searchTerm = '';
    public $filterMonth;
    public $filterYear;

    public function mount()
    {
        $this->filterMonth = now()->format('m');
        $this->filterYear = now()->format('Y');
    }

    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    public function updatingFilterMonth()
    {
        $this->resetPage();
    }

    public function updatingFilterYear()
    {
        $this->resetPage();
    }

    public function showDetail($id)
    {
        $this->detailUpdate = StockUpdate::with(['user', 'items.product'])->find($id);
        $this->detailItems = $this->detailUpdate ? $this->detailUpdate->items->toArray() : [];
        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->detailUpdate = null;
        $this->detailItems = [];
    }

    public function showNota($id)
    {
        $update = StockUpdate::find($id);
        if ($update && $update->nota_image) {
            $this->notaImageUrl = asset('storage/' . $update->nota_image);
            $this->showNotaModal = true;
        }
    }

    public function closeNotaModal()
    {
        $this->showNotaModal = false;
        $this->notaImageUrl = '';
    }

    public function exportExcel()
    {
        $updates = StockUpdate::with(['user', 'items'])
            ->whereMonth('created_at', $this->filterMonth)
            ->whereYear('created_at', $this->filterYear)
            ->latest()
            ->get();

        $rows = collect();
        foreach ($updates as $update) {
            foreach ($update->items as $item) {
                $rows->push([
                    'date' => $update->created_at->format('d-m-Y H:i'),
                    'user' => $update->user->name ?? '-',
                    'product_name' => $item->product_name,
                    'qty_added' => $item->qty_added,
                    'purchase_price' => $item->purchase_price,
                    'stock_before' => $item->stock_before,
                    'stock_after' => $item->stock_after,
                    'price_before' => $item->price_before,
                    'price_after' => $item->price_after,
                    'notes' => $update->notes ?? '-',
                ]);
            }
        }

        $monthName = \Carbon\Carbon::createFromFormat('m', $this->filterMonth)->translatedFormat('F');
        $filename = "riwayat-update-stok-{$monthName}-{$this->filterYear}.xlsx";

        return Excel::download(new StockUpdateExport($rows, $this->filterMonth, $this->filterYear), $filename);
    }

    public function render()
    {
        $query = StockUpdate::with(['user', 'items'])
            ->whereMonth('created_at', $this->filterMonth)
            ->whereYear('created_at', $this->filterYear);

        if ($this->searchTerm) {
            $search = $this->searchTerm;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%");
                })->orWhereHas('items', function ($i) use ($search) {
                    $i->where('product_name', 'like', "%{$search}%");
                });
            });
        }

        $history = $query->latest()->paginate(10);

        $years = range(2023, now()->year);

        return view('livewire.dashboard.stock-update-history', compact('history', 'years'));
    }
}
