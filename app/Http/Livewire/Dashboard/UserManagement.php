<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\User;
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
    public $role = 'sysadmin';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
    ];

    protected $messages = [
        'name.required' => 'This field is required.',
        'email.required' => 'This field is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email has already been taken.',
        'password.required' => 'This field is required.',
        'password.min' => 'Password must be at least 8 characters.',
        'password.confirmed' => 'Password confirmation does not match.',
    ];

    public function mount()
    {
        // Check if user is master_admin
        if (auth()->user()->role !== 'master_admin') {
            abort(403, 'Unauthorized access');
        }
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
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'userId']);
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
            'role' => 'sysadmin',
        ]);

        $this->closeModal();

        $this->dispatchBrowserEvent('swal', [
            'title' => 'User Created',
            'text' => 'User successfully created.',
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
        $this->userId = $id;

        $this->modalType = 'update';
        $this->openModal();
    }

    public function update()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
        ];

        if ($this->password) {
            $rules['password'] = 'min:8|confirmed';
        }

        $this->validate($rules);

        $user = User::findOrFail($this->userId);
        $userData = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if ($this->password) {
            $userData['password'] = Hash::make($this->password);
        }

        $user->update($userData);

        $this->closeModal();
        
        $this->dispatchBrowserEvent('swal', [
            'title' => 'User Updated',
            'text' => 'User successfully updated.',
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
                'text' => 'You cannot delete your own account.',
                'icon' => 'error'
            ]);
            return;
        }

        $user->delete();

        $this->dispatchBrowserEvent('swal', [
            'title' => 'User Deleted',
            'text' => 'User successfully deleted.',
            'icon' => 'success'
        ]);

        $this->resetPage();
    }

    public function render()
    {
        $users = User::where('role', 'sysadmin')
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