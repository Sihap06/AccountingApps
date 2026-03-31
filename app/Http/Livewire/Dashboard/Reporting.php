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
        
        $user = auth()->user();
        if ($type !== null) {
            $this->tabActive = $type;
        } else {
            // Priority default fallback Tab selection
            if ($user->hasPermission('reporting_transaction') || $user->isOwner()) {
                $this->tabActive = 'transaction';
            } elseif ($user->hasPermission('reporting_expenditure')) {
                $this->tabActive = 'expenditure';
            } elseif ($user->hasPermission('reporting_income_fee')) {
                $this->tabActive = 'income';
            } elseif ($user->hasPermission('reporting_export')) {
                $this->tabActive = 'export';
            } else {
                $this->tabActive = 'restricted';
            }
        }
    }

    public function render()
    {
        return view('livewire.dashboard.reporting')
            ->layout('components.layouts.dashboard');
    }
}
