<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\User;
use App\Models\Permission;
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
    public $role = 'kasir';
    public $selectedPermissions = [];
    public $allPermissions = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
        'role' => 'required|in:kasir,manajer,owner',
    ];

    protected $messages = [
        'name.required' => 'Nama wajib diisi.',
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Masukkan alamat email yang valid.',
        'email.unique' => 'Email sudah digunakan.',
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal 8 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
        'role.required' => 'Role wajib dipilih.',
        'role.in' => 'Role tidak valid.',
    ];

    public function mount()
    {
        if (!auth()->user()->hasPermission('user_management')) {
            abort(403, 'Unauthorized access');
        }

        $this->allPermissions = Permission::orderBy('sort_order')->get()->groupBy('group')->toArray();
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
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'userId', 'selectedPermissions']);
        $this->role = 'kasir';
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

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
        ]);

        // Sync permissions (owner gets all by default, no need to store)
        if ($this->role !== 'owner') {
            $user->permissions()->sync($this->selectedPermissions);
        }

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
        $this->role = $user->role;
        $this->userId = $id;
        $this->selectedPermissions = $user->permissions->pluck('id')->toArray();

        $this->modalType = 'update';
        $this->openModal();
    }

    public function update()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'role' => 'required|in:kasir,manajer,owner',
        ];

        if ($this->password) {
            $rules['password'] = 'min:8|confirmed';
        }

        $this->validate($rules);

        $user = User::findOrFail($this->userId);
        $userData = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];

        if ($this->password) {
            $userData['password'] = Hash::make($this->password);
        }

        $user->update($userData);

        // Sync permissions
        if ($this->role !== 'owner') {
            $user->permissions()->sync($this->selectedPermissions);
        } else {
            $user->permissions()->detach();
        }

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

        $user->permissions()->detach();
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
