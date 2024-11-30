<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;

class Reporting extends Component
{
    public $tabActive = 'transaction';
    public $selectedId = null;

    public function changeActiveTab($tab)
    {
        $this->tabActive = $tab;

        $this->dispatchBrowserEvent('refresh-child-component');
    }

    public function mount($id = null, $type = null)
    {
        $this->selectedId = $id;
        if ($type !== null) {
            $this->tabActive = $type;
        }
    }

    public function render()
    {
        return view('livewire.dashboard.reporting')
            ->layout('components.layouts.dashboard');
    }
}
