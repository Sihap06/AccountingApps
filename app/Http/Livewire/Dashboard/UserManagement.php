<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\User;
use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class UserManagement extends Component
{
    use WithPagination;

    public $searchTerm;
    public $isOpen = false;
    public $modalType = 'store';
    public $userId;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $role_id;
    public $roles = [];

    protected $listeners = ['confirmDeleteUser'];

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
        'role_id' => 'required|exists:roles,id',
    ];

    protected $messages = [
        'name.required' => 'Nama wajib diisi.',
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Masukkan alamat email yang valid.',
        'email.unique' => 'Email sudah digunakan.',
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal 8 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
        'role_id.required' => 'Role wajib dipilih.',
        'role_id.exists' => 'Role tidak valid.',
    ];

    public function mount()
    {
        $this->roles = Role::orderBy('name')->get();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetValidation();
    }

    public function resetField()
    {
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'userId', 'role_id']);
        $this->resetValidation();
    }

    public function create()
    {
        $this->modalType = 'store';
        $this->resetField();
        $this->openModal();
    }

    public function store()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role_id' => $this->role_id,
        ]);

        $this->closeModal();

        $this->dispatchBrowserEvent('swal', [
            'title' => 'User Dibuat',
            'text' => 'User berhasil dibuat.',
            'icon' => 'success'
        ]);

        $this->resetField();
        $this->resetPage();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        $this->name = $user->name;
        $this->email = $user->email;
        $this->role_id = $user->role_id;
        $this->userId = $id;

        $this->modalType = 'update';
        $this->openModal();
    }

    public function update()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'role_id' => 'required|exists:roles,id',
        ];

        if ($this->password) {
            $rules['password'] = 'min:8|confirmed';
        }

        $this->validate($rules);

        $user = User::findOrFail($this->userId);
        $userData = [
            'name' => $this->name,
            'email' => $this->email,
            'role_id' => $this->role_id,
        ];

        if ($this->password) {
            $userData['password'] = Hash::make($this->password);
        }

        $user->update($userData);

        $this->closeModal();

        $this->dispatchBrowserEvent('swal', [
            'title' => 'User Diperbarui',
            'text' => 'User berhasil diperbarui.',
            'icon' => 'success'
        ]);

        $this->resetField();
        $this->resetPage();
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting self
        if ($user->id === auth()->id()) {
            $this->dispatchBrowserEvent('swal', [
                'title' => 'Error',
                'text' => 'Tidak bisa menghapus akun sendiri.',
                'icon' => 'error'
            ]);
            return;
        }

        // Prevent non-owner from deleting owner
        if ($user->role === 'owner' && !auth()->user()->isOwner()) {
            $this->dispatchBrowserEvent('swal', [
                'title' => 'Error',
                'text' => 'Tidak bisa menghapus akun owner.',
                'icon' => 'error'
            ]);
            return;
        }

        // Check related data
        $relatedData = $user->getRelatedDataInfo();
        if (!empty($relatedData)) {
            $dataList = implode(', ', $relatedData);
            $this->dispatchBrowserEvent('swal-confirm-delete', [
                'title' => 'User Memiliki Data Terkait',
                'text' => "User ini memiliki data: {$dataList}. User akan dinonaktifkan (soft delete) dan datanya tetap tersimpan. Lanjutkan?",
                'userId' => $id,
            ]);
            return;
        }

        // No related data — show simple confirmation via SweetAlert
        $this->dispatchBrowserEvent('swal-confirm-delete', [
            'title' => 'Hapus User?',
            'text' => "Yakin ingin menghapus user \"{$user->name}\"? Tindakan ini dapat dibatalkan.",
            'userId' => $id,
        ]);
    }

    /**
     * Called from SweetAlert confirmation event.
     */
    public function confirmDeleteUser($id)
    {
        $user = User::findOrFail($id);

        // Re-validate permissions
        if ($user->id === auth()->id()) {
            return;
        }
        if ($user->role && strtolower($user->role->name) === 'owner' && !auth()->user()->isOwner()) {
            return;
        }

        // Soft delete (permissions persist in roles, so no need to detach user permissions)
        $user->delete();

        $this->dispatchBrowserEvent('swal', [
            'title' => 'User Dihapus',
            'text' => 'User berhasil dihapus.',
            'icon' => 'success'
        ]);

        $this->resetPage();
    }

    public function render()
    {
        $users = User::where('id', '!=', auth()->id())
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return view('livewire.dashboard.user-management', compact('users'))
            ->layout('components.layouts.dashboard');
    }
}
