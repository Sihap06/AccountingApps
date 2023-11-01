<?php

namespace App\Http\Livewire\Dashboard\Inventory;

use Livewire\Component;

class Edit extends Component
{
    public function mount($id)
    {
        dd($id);
    }

    public function render()
    {
        return view('livewire.dashboard.inventory.edit');
    }
}
