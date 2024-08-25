<?php

namespace App\Http\Livewire\Dashboard\LogActivity;

use App\Models\LogActivityExpenditure;
use Livewire\Component;

class Expenditure extends Component
{
    public function render()
    {
        $data = LogActivityExpenditure::all();
        return view('livewire.dashboard.log-activity.expenditure', compact('data'));
    }
}
