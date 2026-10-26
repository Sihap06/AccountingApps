<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\PendingChange;
use App\Models\VerificationLog;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Verification extends Component
{
    use WithPagination;

    public $statusFilter = '';
    public $typeFilter = '';
    public $selectedChanges = [];
    public $selectAll = false;
    public $showDetailModal = false;
    public $selectedChange = null;
    public $verificationNotes = '';
    public $rejectNotes = '';
    public $pendingCount = 0;

    protected $listeners = ['refreshData' => '$refresh'];

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function mount()
    {
        if (!Auth::user()->hasPermission('verification')) {
            abort(403, 'Unauthorized');
        }
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedChanges = $this->getPendingChanges()
                ->where('status', 'pending')
                ->pluck('id')
                ->toArray();
        } else {
            $this->selectedChanges = [];
        }
    }

    public function getPendingChanges()
    {
        $query = PendingChange::with(['requestedBy', 'verifiedBy']);

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->typeFilter) {
            $query->where('changeable_type', $this->typeFilter);
        }

        // Order by status (pending first) then by date
        return $query->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->orderBy('requested_at', 'desc')
            ->paginate(10);
    }

    public function getPendingCount()
    {
        return PendingChange::where('status', 'pending')->count();
    }

    public function showDetail($changeId)
    {
        $this->selectedChange = PendingChange::with(['requestedBy', 'verifiedBy', 'verificationLogs.verifiedBy'])
            ->find($changeId);
        $this->showDetailModal = true;
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->selectedChange = null;
        $this->verificationNotes = '';
        $this->rejectNotes = '';
    }

    public function approve()
    {
        if (!$this->selectedChange) return;

        DB::beginTransaction();
        try {
            $this->selectedChange->approve(Auth::id(), $this->verificationNotes);

            VerificationLog::logVerification(
                $this->selectedChange,
                'approved',
                Auth::id(),
                $this->verificationNotes
            );

            $this->selectedChange->applyChange();

            DB::commit();

            $this->dispatchBrowserEvent('swal', [
                'title' => 'Berhasil!',
                'text' => 'Perubahan berhasil disetujui dan diterapkan.',
                'icon' => 'success'
            ]);

            $this->closeDetail();
            $this->emit('refreshData');
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('swal', [
                'title' => 'Gagal!',
                'text' => 'Gagal menerapkan perubahan: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    public function reject()
    {
        if (!$this->selectedChange || !$this->rejectNotes) return;

        DB::beginTransaction();
        try {
            $this->selectedChange->reject(Auth::id(), $this->rejectNotes);

            VerificationLog::logVerification(
                $this->selectedChange,
                'rejected',
                Auth::id(),
                $this->rejectNotes
            );

            DB::commit();

            $this->dispatchBrowserEvent('swal', [
                'title' => 'Berhasil!',
                'text' => 'Perubahan berhasil ditolak.',
                'icon' => 'success'
            ]);

            $this->closeDetail();
            $this->emit('refreshData');
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('swal', [
                'title' => 'Gagal!',
                'text' => 'Gagal menolak perubahan: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    public function bulkApprove()
    {
        if (empty($this->selectedChanges)) return;

        $approved = 0;
        $failed = 0;

        foreach ($this->selectedChanges as $changeId) {
            $pendingChange = PendingChange::find($changeId);

            if ($pendingChange && $pendingChange->isPending()) {
                DB::beginTransaction();
                try {
                    $pendingChange->approve(Auth::id());

                    VerificationLog::logVerification(
                        $pendingChange,
                        'approved',
                        Auth::id(),
                        'Bulk approval'
                    );

                    $pendingChange->applyChange();

                    DB::commit();
                    $approved++;
                } catch (\Exception $e) {
                    DB::rollBack();
                    $failed++;
                }
            }
        }

        $message = "Berhasil menyetujui {$approved} perubahan.";
        if ($failed > 0) {
            $message .= " {$failed} gagal.";
        }

        $this->dispatchBrowserEvent('swal', [
            'title' => $failed > 0 ? 'Perhatian!' : 'Berhasil!',
            'text' => $message,
            'icon' => $failed > 0 ? 'warning' : 'success'
        ]);

        $this->selectedChanges = [];
        $this->selectAll = false;
        $this->emit('refreshData');
    }

    /**
     * Columns holding an amount of money, rendered as Rupiah in the
     * old/new data comparison.
     */
    private const MONEY_KEYS = [
        'biaya', 'modal', 'untung', 'potongan', 'fee_teknisi',
        'harga', 'harga_jual', 'total',
    ];

    /**
     * Renders one snapshot value for the comparison panels.
     */
    public function formatValue($key, $value)
    {
        if (is_array($value)) {
            return json_encode($value);
        }

        if (in_array($key, self::MONEY_KEYS, true) && is_numeric($value)) {
            return 'Rp ' . number_format((float) $value, 0, ',', '.');
        }

        return $value;
    }

    public function render()
    {
        $this->pendingCount = $this->getPendingCount();

        // dd($this->getPendingChanges());

        return view('livewire.dashboard.verification', [
            'pendingChanges' => $this->getPendingChanges(),
            'pendingCount' => $this->pendingCount
        ])->layout('components.layouts.dashboard');
    }
}
