<div class="w-full px-6 py-4 mx-auto flex flex-col h-screen">
    <div class="flex justify-between items-center">
        <div class="mb-0 border-b-0 border-solid">
            <h5 class="mb-1 font-serif">User Management</h5>
            <p class="mb-0 text-sm leading-normal dark:text-white dark:opacity-60 font-serif">
                {{ \Carbon\Carbon::now()->format('l, d M Y') }}
            </p>
        </div>
        <div class="flex items-center md:ml-auto md:pr-4"></div>
    </div>

    <div class="flex flex-wrap -mx-3 mt-6 custom-height">
        <div class="flex-none w-full max-w-full px-3 h-full">
            <div
                class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border h-full">
                <div
                    class="block md:flex w-full justify-between items-center p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <button wire:click='create'
                        class="px-8 py-2 text-xs font-bold leading-normal text-center text-white capitalize transition-all ease-in rounded-lg shadow-md bg-slate-700 bg-150 hover:shadow-xs hover:-translate-y-px flex gap-x-2 items-center mb-2 md:mb-0">
                        <div wire:loading wire:target='create'>
                            <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                role="status">
                                <span
                                    class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                            </div>
                        </div>
                        Tambah User
                    </button>
                    <div class="flex w-full md:w-4/12 items-center gap-x-3">
                        <input type="text" wire:model.debounce.500ms="searchTerm"
                            class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"
                            placeholder="Cari nama atau email" />
                    </div>
                </div>

                <div class="flex-auto px-0 pt-0 mt-4 pb-4 overflow-auto h-full">
                    <div class="p-0">
                        <table
                            class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                            <thead class="align-bottom">
                                <tr>
                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        No</th>
                                    <th
                                        class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Nama</th>
                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Email</th>
                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Role</th>
                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Dibuat</th>
                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $index => $user)
                                    <tr wire:key='{{ $index . time() }}' wire:loading.remove
                                        wire:target='gotoPage, previousPage, nextPage, searchTerm, delete'>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                            </span>
                                        </td>
                                        <td
                                            class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <div class="flex px-2 py-1">
                                                <div class="flex flex-col justify-center">
                                                    <h6 class="mb-0 text-sm leading-normal dark:text-white">
                                                        {{ $user->name }}
                                                    </h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                {{ $user->email }}
                                            </span>
                                        </td>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            @php
                                                $roleBadgeColors = [
                                                    'owner' => 'from-purple-600 to-indigo-400',
                                                    'manajer' => 'from-blue-600 to-cyan-400',
                                                    'kasir' => 'from-green-600 to-lime-400',
                                                ];
                                                $badgeColor = $roleBadgeColors[$user->role] ?? 'from-gray-600 to-gray-400';
                                            @endphp
                                            <span class="px-2 py-1 text-xs rounded-full bg-gradient-to-tl {{ $badgeColor }} text-white">
                                                {{ strtoupper($user->role) }}
                                            </span>
                                        </td>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                {{ $user->created_at->format('d-m-Y H:i') }}
                                            </span>
                                        </td>
                                        <td
                                            class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <button type="button" wire:click='edit({{ $user->id }})'
                                                class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                Edit
                                            </button>
                                            @if($user->id !== auth()->id())
                                                <span class="mx-2">|</span>
                                                <button type="button" wire:click='delete({{ $user->id }})'
                                                    onclick="return confirm('Yakin ingin menghapus user ini?') || event.stopImmediatePropagation()"
                                                    class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-red-500">
                                                    Hapus
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if ($users->isEmpty())
                            <div class="text-center py-8">
                                <p class="text-gray-500">Tidak ada user ditemukan</p>
                            </div>
                        @endif
                    </div>

                    <div class="px-6 mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if($isOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                    {{ $modalType === 'store' ? 'Tambah User Baru' : 'Edit User' }}
                                </h3>

                                <form class="space-y-4">
                                    <div>
                                        <x-ui.input-default
                                            label="Nama"
                                            name="name"
                                            type="text"
                                            wire:model="name"
                                            placeholder="Masukkan nama user"
                                            :error="$errors->first('name')"
                                            :required="true" />
                                    </div>

                                    <div>
                                        <x-ui.input-default
                                            label="Email"
                                            name="email"
                                            type="email"
                                            wire:model="email"
                                            placeholder="user@example.com"
                                            :error="$errors->first('email')"
                                            :required="true" />
                                    </div>

                                    <div>
                                        <x-ui.input-default
                                            label="Password{{ $modalType === 'update' ? ' (Kosongkan jika tidak diubah)' : '' }}"
                                            name="password"
                                            type="password"
                                            wire:model="password"
                                            placeholder="********"
                                            :error="$errors->first('password')"
                                            :required="$modalType === 'store'" />
                                    </div>

                                    <div>
                                        <x-ui.input-default
                                            label="Konfirmasi Password"
                                            name="password_confirmation"
                                            type="password"
                                            wire:model="password_confirmation"
                                            placeholder="********"
                                            :required="$modalType === 'store'" />
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Role <span class="text-red-500">*</span>
                                        </label>
                                        <select wire:model="role"
                                            class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all focus:border-blue-500 focus:outline-none">
                                            <option value="kasir">Kasir</option>
                                            <option value="manajer">Manajer</option>
                                            <option value="owner">Owner</option>
                                        </select>
                                        @error('role')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Permission Assignment -->
                                    @if($role !== 'owner')
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                                Hak Akses Fitur
                                            </label>
                                            <div class="bg-gray-50 rounded-lg p-4 space-y-4 max-h-64 overflow-y-auto">
                                                @foreach($allPermissions as $group => $permissions)
                                                    <div>
                                                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">{{ $group }}</h4>
                                                        <div class="grid grid-cols-2 gap-2">
                                                            @foreach($permissions as $permission)
                                                                <label class="flex items-center space-x-2 cursor-pointer hover:bg-gray-100 rounded p-1.5 transition-colors">
                                                                    <input type="checkbox"
                                                                        wire:model="selectedPermissions"
                                                                        value="{{ $permission['id'] }}"
                                                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                                                    <span class="text-sm text-gray-700">{{ $permission['name'] }}</span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <p class="text-xs text-gray-400 mt-1">Centang fitur yang boleh diakses oleh user ini.</p>
                                        </div>
                                    @else
                                        <div class="bg-purple-50 rounded-lg p-3">
                                            <p class="text-sm text-purple-700">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                Role Owner memiliki akses ke semua fitur secara otomatis.
                                            </p>
                                        </div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click='{{ $modalType === 'store' ? 'store' : 'update' }}'
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            {{ $modalType === 'store' ? 'Buat User' : 'Perbarui User' }}
                        </button>
                        <button type="button" wire:click='closeModal'
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
