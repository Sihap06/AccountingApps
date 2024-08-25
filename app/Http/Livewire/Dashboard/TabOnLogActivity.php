<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;

class TabOnLogActivity extends Component
{

    public $tabActive = 'inventory';

    public function changeActiveTab($tab)
    {
        $this->tabActive = $tab;
    }

    public function render()
    {
        return view('livewire.dashboard.tab-on-log-activity')
            ->layout('components.layouts.dashboard');
    }
}
