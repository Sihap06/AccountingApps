<?php

namespace App\Http\Livewire\Dashboard\Reporting;

use App\Models\Transaction as ModelsTransaction;
use Livewire\Component;
use Livewire\WithPagination;

class Transaction extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public function render()
    {
        $data = ModelsTransaction::paginate(10);
        return view('livewire.dashboard.reporting.transaction', compact('data'));
    }
}
