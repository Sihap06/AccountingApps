<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;

class Reporting extends Component
{
    public $tabActive = 'transaction';

    public function changeActiveTab($tab)
    {
        $this->tabActive = $tab;
    }

    public function render()
    {
        return view('livewire.dashboard.reporting')
            ->layout('components.layouts.dashboard');
    }
}
