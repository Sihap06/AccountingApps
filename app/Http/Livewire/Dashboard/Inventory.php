<?php

namespace App\Http\Livewire\Dashboard;

use App\Http\Controllers\ProductController;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Inventory extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public function render()
    {
        $data = Product::paginate(10);
        return view('livewire.dashboard.inventory', compact('data'))
            ->layout('components.layouts.dashboard');
    }

    public function delete($id)
    {
        app(ProductController::class)->deleteProduct($id);

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Success',
            'text' => "successfully inventory deleted.",
            'icon' => 'success'
        ]);
    }
}
