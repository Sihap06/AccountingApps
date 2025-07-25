<?php

namespace App\Http\Livewire\Dashboard\Reporting;

use App\Models\Expenditure as ModelsExpenditure;
use App\Models\LogActivityExpenditure;
use App\Models\PendingChange;
use Carbon\Carbon;
use Livewire\Component;

class Expenditure extends Component
{

    public $isAdd = false;
    public $isEdit = false;

    public $currentId;
    public $tanggal;
    public $jenis;
    public $total;
    
    public $reason = '';
    public $showReasonModal = false;
    public $pendingAction = null;
    public $pendingActionData = null;

    public $totalAmount;

    public $selectedYear = '';
    public $selectedMonth = '';

    protected $listeners = ['refreshComponent' => '$refresh'];

    protected $rules = [
        'tanggal' => 'required',
        'jenis' => 'required',
        'total' => 'required'
    ];

    public function resetValue()
    {
        $this->tanggal = '';
        $this->jenis = '';
        $this->total = '';
    }

    public function store()
    {
        $validateData = $this->validate();
        $currencyString = preg_replace("/[^0-9]/", "", $this->total);
        $convertedCurrency = (int)$currencyString;
        $validateData['total'] = $convertedCurrency;
        $validateData['created_by'] = auth()->user()->id;

        // Check if user is sysadmin (operator) - needs verification
        if (auth()->user()->role === 'sysadmin') {
            // Create pending change instead of direct create
            PendingChange::create([
                'changeable_type' => ModelsExpenditure::class,
                'changeable_id' => null,
                'action' => 'create',
                'old_data' => null,
                'new_data' => $validateData,
                'requested_by' => auth()->user()->id,
                'requested_at' => now(),
            ]);

            $this->dispatchBrowserEvent('swal', [
                'title' => 'Success',
                'text' => "Perubahan berhasil disimpan dan menunggu verifikasi.",
                'icon' => 'info'
            ]);
        } else {
            // Master admin can create directly
            ModelsExpenditure::create($validateData);

            LogActivityExpenditure::create([
                'user' => auth()->user()->name,
                'activity' => 'store',
                'jenis' => $validateData['jenis'],
                'new_jenis' => $validateData['jenis'],
                'new_tanggal' => $validateData['tanggal'],
                'new_total' => $validateData['total'],
            ]);

            $this->dispatchBrowserEvent('swal', [
                'title' => 'Success',
                'text' => "successfully expenditure created.",
                'icon' => 'success'
            ]);
        }

        $this->setShowAdd();
    }

    public function edit($id)
    {
        $data = ModelsExpenditure::findOrFail($id);

        $this->tanggal = $data->tanggal;
        $this->jenis = $data->jenis;
        $this->total = $data->total;

        $this->currentId = $id;

        if (!$this->isEdit) {
            $this->setShowEdit();
        }
    }

    public function update()
    {
        $validateData = $this->validate();
        $currencyString = preg_replace("/[^0-9]/", "", $this->total);
        $convertedCurrency = (int)$currencyString;
        $validateData['total'] = $convertedCurrency;

        $expend = ModelsExpenditure::findOrFail($this->currentId);

        // Check if user is sysadmin (operator) - needs verification
        if (auth()->user()->role === 'sysadmin') {
            // Store data and show reason modal
            $this->pendingAction = 'update';
            $this->pendingActionData = [
                'validateData' => $validateData,
                'expend' => $expend->toArray()
            ];
            $this->showReasonModal = true;
            $this->isEdit = false;
            return; // Return early to prevent resetting fields
        } else {
            // Master admin can update directly
            if ($this->jenis !== $expend->jenis || $this->tanggal !== $expend->tanggal || $validateData['total'] !== $expend->total) {
                $log = new LogActivityExpenditure();
                $log->user = auth()->user()->name;
                $log->activity = 'update';

                if ($this->tanggal !== $expend->tanggal) {
                    $log->old_tanggal = $expend->tanggal;
                    $log->new_tanggal = $this->tanggal;
                }

                if ($this->jenis !== $expend->jenis) {
                    $log->old_jenis = $expend->jenis;
                    $log->new_jenis = $this->jenis;
                }

                if ($this->total !== $expend->total) {
                    $log->old_total = $expend->total;
                    $log->new_total = $validateData['total'];
                }
                $log->save();
            }

            ModelsExpenditure::findOrFail($this->currentId)->update($validateData);

            $this->dispatchBrowserEvent('swal', [
                'title' => 'Success',
                'text' => "successfully expenditure updated.",
                'icon' => 'success'
            ]);
        }

        $this->setShowEdit();
    }

    public function delete($id)
    {
        $expend = ModelsExpenditure::findOrFail($id);

        // Check if user is sysadmin (operator) - needs verification
        if (auth()->user()->role === 'sysadmin') {
            // Create pending change instead of direct delete
            PendingChange::create([
                'changeable_type' => ModelsExpenditure::class,
                'changeable_id' => $id,
                'action' => 'delete',
                'old_data' => $expend->toArray(),
                'new_data' => null,
                'requested_by' => auth()->user()->id,
                'requested_at' => now(),
            ]);

            $this->dispatchBrowserEvent('swal', [
                'title' => 'Success',
                'text' => "Permintaan penghapusan berhasil disimpan dan menunggu verifikasi.",
                'icon' => 'info'
            ]);
        } else {
            // Master admin can delete directly
            $expend->delete();

            $this->dispatchBrowserEvent('swal', [
                'title' => 'Success',
                'text' => "successfully expenditure deleted.",
                'icon' => 'success'
            ]);
        }
    }

    public function setShowAdd()
    {
        $this->isAdd = !$this->isAdd;
        $this->isEdit = false;
    }

    public function setShowEdit()
    {
        $this->isEdit = !$this->isEdit;
        $this->isAdd = false;
    }

    public function mount()
    {
        $this->selectedYear = Carbon::now()->format('Y');
        $this->selectedMonth = Carbon::now()->format('m');
    }

    public function render()
    {
        $query = ModelsExpenditure::whereMonth('tanggal', $this->selectedMonth)
            ->whereYear('tanggal', $this->selectedYear);

        if (auth()->user()->role != 'master_admin') {
            $query = $query->where('created_by', auth()->user()->id);
        }

        $data = $query->get();
        $this->totalAmount = $data->sum('total');

        $listYear = ['2023', '2024', '2025', '2026', '2027'];
        $listMonth = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        return view('livewire.dashboard.reporting.expenditure', ['data' => $data, 'listMonth' => $listMonth, 'listYear' => $listYear]);
    }
    
    public function submitReason()
    {
        $this->validate([
            'reason' => 'required|min:10'
        ], [
            'reason.required' => 'Alasan harus diisi',
            'reason.min' => 'Alasan minimal 10 karakter'
        ]);

        if ($this->pendingAction === 'update') {
            $validateData = $this->pendingActionData['validateData'];
            $expend = $this->pendingActionData['expend'];

            // Create pending change with reason
            PendingChange::create([
                'changeable_type' => ModelsExpenditure::class,
                'changeable_id' => $this->currentId,
                'action' => 'update',
                'old_data' => $expend,
                'new_data' => array_merge($expend, $validateData),
                'reason' => $this->reason,
                'requested_by' => auth()->user()->id,
                'requested_at' => now(),
            ]);

            $this->dispatchBrowserEvent('swal', [
                'title' => 'Success',
                'text' => "Perubahan berhasil disimpan dan menunggu verifikasi.",
                'icon' => 'info'
            ]);
        }

        $this->closeReasonModal();
        $this->resetValue();
        $this->emit('refreshComponent');
    }

    public function closeReasonModal()
    {
        $this->showReasonModal = false;
        $this->reason = '';
        $this->pendingAction = null;
        $this->pendingActionData = null;
    }
}
