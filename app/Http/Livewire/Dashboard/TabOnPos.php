<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;

class TabOnPos extends Component
{
    public $tabActive = 'point-of-sales';

    public function changeActiveTab($tab)
    {
        $this->tabActive = $tab;

        $this->dispatchBrowserEvent('refresh-child-component');
        $this->dispatchBrowserEvent('reInitTwElement');
    }

    public function render()
    {
        return view('livewire.dashboard.tab-on-pos')
            ->layout('components.layouts.dashboard');
    }
}
