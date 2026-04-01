<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Role;
use App\Models\Permission;
use Livewire\Component;
use Livewire\WithPagination;

class RoleManagement extends Component
{
    use WithPagination;

    public $searchTerm;
    public $isOpen = false;
    public $modalType = 'store';
    public $roleId;
    public $name;
    public $description;
    public $selectedPermissions = [];
    public $allPermissions = [];

    protected $listeners = ['confirmDeleteRole'];

    protected $rules = [
        'name' => 'required|string|max:255|unique:roles,name',
        'description' => 'nullable|string|max:255',
    ];

    protected $messages = [
        'name.required' => 'Nama role wajib diisi.',
        'name.unique' => 'Nama role sudah digunakan.',
    ];

    public function mount()
    {
        // Menggunakan daftar permisson yang dikelompokkan
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
        $this->reset(['name', 'description', 'roleId', 'selectedPermissions']);
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

        $role = Role::create([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        // Simpan permission yang dicentang
        if (strtolower($role->name) !== 'owner') {
            $role->permissions()->sync($this->selectedPermissions);
        }

        $this->closeModal();

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Role Dibuat',
            'text' => 'Role baru berhasil dibuat.',
            'icon' => 'success'
        ]);

        $this->resetField();
        $this->resetPage();
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);

        $this->name = $role->name;
        $this->description = $role->description;
        $this->roleId = $id;
        
        // Ambil checklist permission sebelumnya
        $this->selectedPermissions = $role->permissions->pluck('id')->toArray();

        $this->modalType = 'update';
        $this->openModal();
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $this->roleId,
            'description' => 'nullable|string|max:255',
        ]);

        $role = Role::findOrFail($this->roleId);

        // Jangan izinkan mengubah NAMA dari role core system jika namanya awalnya berasal dari sistem
        // menggunakan pengecekan getOriginal('name')
        $protectedRoles = ['owner', 'manajer', 'kasir'];
        $originalName = strtolower($role->getOriginal('name'));
        
        if (in_array($originalName, $protectedRoles) && strtolower($this->name) !== $originalName) {
             $this->dispatchBrowserEvent('swal', [
                'title' => 'Dilarang',
                'text' => 'Batas keamaan sistem: Anda dilarang mengubah ejaan nama dari Role sistem utama (Owner, Manajer, Kasir)! Silahkan ubah deskripsi dan centang fiturnya saja.',
                'icon' => 'error'
            ]);
            return;
        }

        $role->update([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        // Update izin (Owner otomatis punya semua berkat logic role system)
        if (strtolower($role->name) !== 'owner') {
            $role->permissions()->sync($this->selectedPermissions);
        }

        $this->closeModal();

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Role Diperbarui',
            'text' => 'Data Role berhasil diperbarui.',
            'icon' => 'success'
        ]);

        $this->resetField();
        $this->resetPage();
    }

    public function delete($id)
    {
        $role = Role::findOrFail($id);

        // Pencegahan menghapus role standar sistem
        if (in_array(strtolower($role->name), ['owner', 'manajer', 'kasir'])) {
            $this->dispatchBrowserEvent('swal', [
                'title' => 'Ditolak',
                'text' => 'Role standar sistem tidak dapat dihapus.',
                'icon' => 'error'
            ]);
            return;
        }

        if ($role->users()->exists()) {
            $this->dispatchBrowserEvent('swal', [
                'title' => 'Gagal Menghapus',
                'text' => 'Role sedang digunakan oleh ' . $role->users()->count() . ' user. Pindahkan user terlebih dahulu.',
                'icon' => 'error'
            ]);
            return;
        }

        $this->dispatchBrowserEvent('swal-confirm-delete', [
            'title' => 'Hapus Role?',
            'text' => "Yakin ingin menghapus role \"{$role->name}\"? Izin akses pada role ini akan ikut terhapus.",
            'roleId' => $id,
        ]);
    }

    public function confirmDeleteRole($id)
    {
        $role = Role::findOrFail($id);

        if (in_array(strtolower($role->name), ['owner', 'manajer', 'kasir']) || $role->users()->exists()) {
            return;
        }

        $role->permissions()->detach();
        $role->delete();

        $this->dispatchBrowserEvent('swal', [
            'title' => 'Role Dihapus',
            'text' => 'Role berhasil dihapus sepenuhnya.',
            'icon' => 'success'
        ]);

        $this->resetPage();
    }

    public function render()
    {
        $roles = Role::where('name', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('description', 'like', '%' . $this->searchTerm . '%')
            ->orderBy('created_at', 'ASC')
            ->paginate(10);

        return view('livewire.dashboard.role-management', compact('roles'))
            ->layout('components.layouts.dashboard');
    }
}
