<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentMethods extends Component
{
    use WithPagination;

    public $code;
    public $name;
    public $paymentMethodId;
    public $isOpen = false;
    public $search = '';

    protected $rules = [
        'code' => 'required|string|max:20|unique:payment_methods,code',
        'name' => 'required|string|max:100'
    ];

    public function mount()
    {
        if (Auth::user()->role !== 'master_admin') {
            abort(403, 'Unauthorized');
        }
    }

    public function render()
    {
        $paymentMethods = PaymentMethod::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('code', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.dashboard.payment-methods', [
            'paymentMethods' => $paymentMethods
        ])->layout('components.layouts.dashboard');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->code = '';
        $this->name = '';
        $this->paymentMethodId = '';
    }

    public function store()
    {
        $this->validate();

        PaymentMethod::create([
            'code' => $this->code,
            'name' => $this->name
        ]);

        session()->flash('message', 'Metode pembayaran berhasil ditambahkan.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        $this->paymentMethodId = $id;
        $this->code = $paymentMethod->code;
        $this->name = $paymentMethod->name;

        $this->openModal();
    }

    public function update()
    {
        if ($this->paymentMethodId) {
            $this->validate([
                'code' => 'required|string|max:20|unique:payment_methods,code,' . $this->paymentMethodId,
                'name' => 'required|string|max:100'
            ]);

            $paymentMethod = PaymentMethod::find($this->paymentMethodId);
            $paymentMethod->update([
                'code' => $this->code,
                'name' => $this->name
            ]);

            session()->flash('message', 'Metode pembayaran berhasil diperbarui.');

            $this->closeModal();
            $this->resetInputFields();
        }
    }

    public function delete($id)
    {
        PaymentMethod::find($id)->delete();
        session()->flash('message', 'Metode pembayaran berhasil dihapus.');
    }
}
