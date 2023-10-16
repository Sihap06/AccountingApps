<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;

class PointOfSales extends Component
{
    public function render()
    {
        return view('livewire.dashboard.point-of-sales')
            ->layout('components.layouts.dashboard');
    }
}
