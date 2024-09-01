<?php

namespace App\Http\Livewire\Dashboard\LogActivity;

use App\Models\LogActivityTransaction;
use Livewire\Component;

class Transaction extends Component
{
    public function render()
    {
        $data = LogActivityTransaction::orderby('created_at', 'DESC')->get();
        return view('livewire.dashboard.log-activity.transaction', compact('data'));
    }
}
