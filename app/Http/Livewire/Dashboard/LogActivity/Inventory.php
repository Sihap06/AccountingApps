<?php

namespace App\Http\Livewire\Dashboard\LogActivity;

use App\Models\LogActivityProduct;
use Livewire\Component;

class Inventory extends Component
{
    public function render()
    {
        $data = LogActivityProduct::all();
        return view('livewire.dashboard.log-activity.inventory', compact('data'));
    }
}
