<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\LogActivityProduct;
use Livewire\Component;

class LogActivity extends Component
{
    public function render()
    {
        $data = LogActivityProduct::paginate(10);
        return view('livewire.dashboard.log-activity', compact('data'));
    }
}
