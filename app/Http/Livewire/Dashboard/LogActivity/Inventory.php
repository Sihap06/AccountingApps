<?php

namespace App\Http\Livewire\Dashboard\LogActivity;

use App\Models\LogActivityProduct;
use Livewire\Component;

class Inventory extends Component
{
    public $readyToLoad = false;
    public $logs = [];
    public $page = 1;
    public $perPage = 20;
    public $hasMorePages = true;

    public function loadLogs()
    {
        $this->readyToLoad = true;
        $this->loadMore();
    }

    public function loadMore()
    {
        if (!$this->hasMorePages) {
            return;
        }

        $newLogs = LogActivityProduct::orderBy('created_at', 'DESC')
            ->skip(($this->page - 1) * $this->perPage)
            ->take($this->perPage)
            ->get();

        if ($newLogs->count() < $this->perPage) {
            $this->hasMorePages = false;
        }

        $this->logs = array_merge($this->logs, $newLogs->toArray());
        $this->page++;
    }

    public function render()
    {
        return view('livewire.dashboard.log-activity.inventory', [
            'data' => collect($this->logs)->map(fn($log) => (object) $log),
        ]);
    }
}
