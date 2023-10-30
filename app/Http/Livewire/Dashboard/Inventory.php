<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;

class Inventory extends Component
{
    public function render()
    {
        return view('livewire.dashboard.inventory')
            ->layout('components.layouts.dashboard');
    }
}
