<?php

namespace App\Http\Livewire\Dashboard;

use App\Http\Controllers\ProductController;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Inventory extends Component
{
    use WithPagination;

    public $searchTerm;

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
        $response = app(ProductController::class)->deleteProduct($id);
        $status = $response->getData(true)['status'];
        $message = $response->getData(true)['message'];

        $this->dispatchBrowserEvent('swal', [
            'title' => $status,
            'text' => $message,
            'icon' => $status
        ]);
    }
}
