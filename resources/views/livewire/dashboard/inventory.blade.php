<div class="flex flex-wrap -mx-3 custom-height" wire:init="loadProducts">
    <div class="flex-none w-full max-w-full px-3 h-full">
        <div
            class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border h-full">
            <div
                class="block md:flex w-full justify-between items-center p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <div class="flex items-center gap-x-2 mb-2 md:mb-0">
                    <button wire:click='openModal' type="button"
                        class="px-8 py-2 text-xs font-bold leading-normal text-center text-white capitalize transition-all ease-in rounded-lg shadow-md bg-slate-700 bg-150 hover:shadow-xs hover:-translate-y-px flex items-center gap-x-2">
                        <div wire:loading wire:target='openModal'>
                            <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                role="status">
                                <span
                                    class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Memuat...</span>
                            </div>
                        </div>
                        <span>Inventaris Baru</span>
                    </button>
                    <button wire:click='openStockUpdateModal' type="button"
                        class="px-6 py-2 text-xs font-bold leading-normal text-center text-white capitalize transition-all ease-in rounded-lg shadow-md bg-green-600 hover:shadow-xs hover:-translate-y-px flex items-center gap-x-2">
                        <div wire:loading wire:target='openStockUpdateModal'>
                            <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                role="status"></div>
                        </div>
                        <i class="fas fa-boxes" wire:loading.remove wire:target='openStockUpdateModal'></i>
                        <span>Update Stok</span>
                    </button>
                </div>
                <div class="flex w-full md:w-4/12 items-center gap-x-3">
                    <input type="text" wire:model.debounce.500ms="searchTerm"
                        class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"
                        placeholder="Masukkan nama atau kode" />
                </div>
            </div>

            @if($readyToLoad && count($data) > 0)
                <div class="px-6 pt-4">
                    <div class="inline-flex items-center gap-2 bg-blue-50 rounded-lg px-4 py-2">
                        <span class="text-xs font-semibold text-blue-700">Total Modal:</span>
                        <span class="text-sm font-bold text-blue-800">Rp {{ number_format($data->sum(function($item) { return ($item['harga'] ?? 0) * ($item['stok'] ?? 0); })) }}</span>
                    </div>
                </div>
            @endif

            <div class="flex-auto px-0 pt-0 mt-4 overflow-auto h-full">
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
                                    Kode</th>
                                <th
                                    class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Harga Beli</th>
                                <th
                                    class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Harga Jual</th>
                                <th
                                    class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Stok</th>
                                <th
                                    class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Total Modal</th>
                                <th
                                    class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Return Stock</th>
                                <th
                                    class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$readyToLoad)
                                {{-- Show loading skeleton when data is not loaded yet --}}
                                @for ($i = 0; $i < 5; $i++)
                                    <tr>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <div class="h-5 w-12 rounded bg-gray-200 animate-pulse"></div>
                                        </td>
                                        <td
                                            class="p-2 text-left align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <div class="h-5 w-32 rounded bg-gray-200 animate-pulse"></div>
                                        </td>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <div class="h-5 w-24 rounded bg-gray-200 animate-pulse"></div>
                                        </td>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <div class="h-5 w-20 rounded bg-gray-200 animate-pulse"></div>
                                        </td>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <div class="h-5 w-20 rounded bg-gray-200 animate-pulse"></div>
                                        </td>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <div class="h-5 w-16 rounded bg-gray-200 animate-pulse"></div>
                                        </td>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <div class="h-5 w-24 rounded bg-gray-200 animate-pulse"></div>
                                        </td>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <div class="h-5 w-16 rounded bg-gray-200 animate-pulse"></div>
                                        </td>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <div class="flex gap-2 justify-center">
                                                <div class="h-8 w-12 rounded bg-gray-200 animate-pulse"></div>
                                                <div class="h-8 w-12 rounded bg-gray-200 animate-pulse"></div>
                                            </div>
                                        </td>
                                    </tr>
                                @endfor
                            @else
                                @foreach ($data as $index => $item)
                                    <tr wire:key='product-{{ $item['id'] ?? $index }}'>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                {{ $index + 1 }}
                                            </span>
                                        </td>
                                        <td
                                            class="p-2 text-left align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                {{ $item['name'] }}
                                            </span>
                                        </td>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                {{ $item['kode'] }}
                                            </span>
                                        </td>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                Rp {{ number_format($item['harga']) }}
                                            </span>
                                        </td>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                Rp {{ number_format($item['harga_jual'] ?? 0) }}
                                            </span>
                                        </td>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">{{ $item['stok'] }}</span>
                                        </td>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                Rp {{ number_format(($item['harga'] ?? 0) * ($item['stok'] ?? 0)) }}
                                            </span>
                                        </td>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            @if ($item['return_stock'] > 0)
                                                <button wire:click="showReturns({{ $item['id'] }})"
                                                    class="text-xs font-semibold leading-tight text-red-600 hover:text-red-800 cursor-pointer underline">
                                                    {{ $item['return_stock'] ?? 0 }}
                                                </button>
                                            @else
                                                <span
                                                    class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                    {{ $item['return_stock'] ?? 0 }}
                                                </span>
                                            @endif
                                        </td>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <div>
                                                <button type="button" wire:click='edit({{ $item['id'] }})'
                                                    class="inline-block px-3 py-2 text-xs mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-primary leading-normal ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md">
                                                    <i class="fas fa-edit" wire:loading.remove
                                                        wire:target='edit({{ $item['id'] }})'></i>
                                                    <div wire:loading wire:target='edit({{ $item['id'] }})'>
                                                        <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                                            role="status">
                                                            <span
                                                                class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                                                        </div>
                                                    </div>
                                                </button>
                                                @if(auth()->user()->isOwner())
                                                <button type="button"
                                                    wire:click="$emit('triggerDelete',{{ $item['id'] }})"
                                                    class="inline-block px-3 py-2 text-xs mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-red-600 leading-normal ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md">
                                                    <i class="fas fa-trash-alt" wire:loading.remove
                                                        wire:target='delete({{ $item['id'] }})'></i>

                                                    <div wire:loading wire:target='delete({{ $item['id'] }})'>
                                                        <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                                            role="status">
                                                            <span
                                                                class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                                                        </div>
                                                    </div>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                                @if (count($data) == 0)
                                    <tr>
                                        <td colspan="9"
                                            class="p-4 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                Tidak ada data inventory
                                            </span>
                                        </td>
                                    </tr>
                                @endif

                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- Load More Trigger --}}
                @if ($readyToLoad && $hasMorePages)
                    <div x-data="{
                        observe() {
                            const observer = new IntersectionObserver((entries) => {
                                entries.forEach(entry => {
                                    if (entry.isIntersecting) {
                                        @this.call('loadMore');
                                    }
                                });
                            });
                            observer.observe(this.$el);
                        }
                    }" x-init="observe" class="p-4 text-center">
                        <div wire:loading wire:target="loadMore"
                            class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite] text-primary">
                            <span
                                class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                        </div>
                        <span wire:loading.remove wire:target="loadMore" class="text-sm text-gray-500">Scroll untuk
                            melihat lebih banyak...</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @if ($isOpen)
        <div class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div>
                        <div class="mt-3 sm:mt-5">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ $action === 'add' ? 'Tambah Inventaris Baru' : 'Edit Inventaris' }}
                            </h3>
                            <div class="flex-auto px-0 pt-0 pb-2">
                                <form wire:submit.prevent="{{ $action === 'add' ? 'store' : 'update' }}">
                                    <!--E-mail input-->
                                    <div class="relative mb-8">
                                        <x-ui.input-default wire:model="name" label="Name" />
                                        @error('name')
                                            <div class="text-red-500 text-sm">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @if($action === 'add')
                                    <div class="relative mb-8">
                                        <x-ui.input-default wire:model="harga" id="harga" label="Harga Beli"
                                            x-data="{
                                                formatNumber: function(event) {
                                                    const input = event.target;
                                                    const value = input.value.replace(/\D/g, ''); // Remove non-numeric characters
                                                    input.value = new Intl.NumberFormat('en-US').format(value);
                                                }
                                            }" x-on:input="formatNumber($event)" />
                                        @error('harga')
                                            <div class="text-red-500 text-sm">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @else
                                    <div class="relative mb-8">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Beli</label>
                                        <input type="text" value="Rp {{ number_format($harga) }}" disabled
                                            class="text-sm block w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 text-gray-500 cursor-not-allowed" />
                                        <p class="text-xs text-gray-400 mt-1">Harga beli hanya bisa diubah melalui fitur Update Stok.</p>
                                    </div>
                                    @endif
                                    <div class="relative mb-8">
                                        <x-ui.input-default wire:model="harga_jual" id="harga_jual"
                                            label="Harga Jual" x-data="{
                                                formatNumber: function(event) {
                                                    const input = event.target;
                                                    const value = input.value.replace(/\D/g, ''); // Remove non-numeric characters
                                                    input.value = new Intl.NumberFormat('en-US').format(value);
                                                }
                                            }"
                                            x-on:input="formatNumber($event)" />
                                        @error('harga_jual')
                                            <div class="text-red-500 text-sm">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @if($action === 'add')
                                    <div class="relative mb-8">
                                        <x-ui.input-default wire:model="stok" id="stok" label="Stock"
                                            x-data="{
                                                formatNumber: function(event) {
                                                    const input = event.target;
                                                    const value = input.value.replace(/\D/g, ''); // Remove non-numeric characters
                                                    input.value = new Intl.NumberFormat('en-US').format(value);
                                                }
                                            }" x-on:input="formatNumber($event)" />
                                        @error('stok')
                                            <div class="text-red-500 text-sm">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @else
                                    <div class="relative mb-8">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                                        <input type="text" value="{{ number_format($stok) }}" disabled
                                            class="text-sm block w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 text-gray-500 cursor-not-allowed" />
                                        <p class="text-xs text-gray-400 mt-1">Stok hanya bisa diubah melalui fitur Update Stok.</p>
                                    </div>
                                    @endif

                                    <div class="flex gap-x-2">
                                        <button type="button" wire:click='closeModal'
                                            class="flex w-full justify-center gap-x-2 items-center rounded bg-gray-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-gray-700 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-gray-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-gray-800 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">
                                            <div wire:loading wire:target='closeModal'>
                                                <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                                    role="status">
                                                    <span
                                                        class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Memuat...</span>
                                                </div>
                                            </div>
                                            Kembali
                                        </button>

                                        <x-ui.button type="submit" title="Simpan" color="primary" wireLoading
                                            formAction="{{ $action === 'add' ? 'store' : 'update' }}" />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif

    {{-- Reason Modal --}}
    @if ($showReasonModal)
        <div class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div>
                        <div class="mt-3 sm:mt-5">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Alasan {{ $pendingAction === 'update' ? 'Perubahan' : 'Penghapusan' }}
                            </h3>
                            <div class="flex-auto px-0 pt-0 pb-2">
                                <form wire:submit.prevent="submitReason">
                                    <div class="relative mb-8 mt-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">
                                            Masukkan alasan
                                            {{ $pendingAction === 'update' ? 'perubahan' : 'penghapusan' }} <span
                                                class="text-red-500">*</span>
                                        </label>
                                        <textarea wire:model="reason" rows="4"
                                            class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"
                                            placeholder="Jelaskan mengapa {{ $pendingAction === 'update' ? 'perubahan' : 'penghapusan' }} ini diperlukan..."></textarea>
                                        @error('reason')
                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="flex gap-x-2">
                                        <button type="button" wire:click='closeReasonModal'
                                            class="flex w-full justify-center gap-x-2 items-center rounded bg-gray-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-gray-700 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-gray-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-gray-800 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">
                                            <div wire:loading wire:target='closeReasonModal'>
                                                <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                                    role="status">
                                                    <span
                                                        class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                                                </div>
                                            </div>
                                            Batal
                                        </button>

                                        <x-ui.button type="submit" title="Submit" color="primary" wireLoading
                                            formAction="submitReason" />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif

    {{-- Product Returns Modal --}}
    @if ($showReturnModal)
        <div class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full sm:p-6">
                    <div class="border-b-2 flex justify-between items-center mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Pengembalian Produk
                        </h3>
                        <button wire:click='closeReturnModal' type="button">
                            <svg width="20px" height="20px" viewBox="-0.5 0 25 25" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 21.32L21 3.32001" stroke="#000000" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M3 3.32001L21 21.32" stroke="#000000" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>

                    <div class="max-h-96 overflow-y-auto">
                        <table class="items-center w-full mb-0 align-top border-collapse text-slate-500">
                            <thead class="align-bottom">
                                <tr>
                                    <th class="px-4 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Aksi
                                    </th>
                                    <th class="px-4 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Tanggal
                                    </th>
                                    <th class="px-4 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Order Transaksi
                                    </th>
                                    <th class="px-4 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Kuantitas
                                    </th>
                                    <th class="px-4 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Alasan Retur
                                    </th>
                                    <th class="px-4 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Diretur Oleh
                                    </th>
                                    <th class="px-4 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Catatan
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($productReturns as $return)
                                <tr>
                                    <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <div class="flex items-center justify-center gap-2">
                                            <button wire:click="$emit('triggerRestoreReturn', {{ $return['id'] }}, {{ $return['quantity'] }})" wire:loading.attr="disabled"
                                                class="px-2.5 py-1 text-xs font-bold text-white bg-green-600 rounded-lg hover:bg-green-700 transition-all disabled:opacity-50"
                                                title="Kembalikan ke stok">
                                                <div wire:loading wire:target="restoreReturnToStock({{ $return['id'] }})" class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent" role="status"></div>
                                                <span wire:loading.remove wire:target="restoreReturnToStock({{ $return['id'] }})"><i class="fas fa-undo-alt"></i></span>
                                            </button>
                                            <button wire:click="$emit('triggerDeleteReturn', {{ $return['id'] }})" wire:loading.attr="disabled"
                                                class="px-2.5 py-1 text-xs font-bold text-white bg-red-500 rounded-lg hover:bg-red-600 transition-all disabled:opacity-50"
                                                title="Hapus return">
                                                <div wire:loading wire:target="deleteReturn({{ $return['id'] }})" class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent" role="status"></div>
                                                <span wire:loading.remove wire:target="deleteReturn({{ $return['id'] }})"><i class="fas fa-trash-alt"></i></span>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <span class="text-xs font-semibold leading-tight text-slate-400">
                                            {{ \Carbon\Carbon::parse($return['created_at'])->format('Y-m-d H:i') }}
                                        </span>
                                    </td>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <span class="text-xs font-semibold leading-tight text-slate-400">
                                            {{ $return['transaction']['order_transaction'] ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <span class="text-xs font-semibold leading-tight text-slate-400">
                                            {{ $return['quantity'] }}
                                        </span>
                                    </td>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <span class="text-xs font-semibold leading-tight text-slate-400 px-2 py-1 bg-red-100 rounded">
                                            {{ ucfirst(str_replace('_', ' ', $return['return_reason'])) }}
                                        </span>
                                    </td>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <span class="text-xs font-semibold leading-tight text-slate-400">
                                            {{ $return['returned_by']['name'] ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <span class="text-xs leading-tight text-slate-400">
                                            {{ $return['notes'] ? \Illuminate\Support\Str::limit($return['notes'], 50) : '-' }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="p-4 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <span class="text-xs font-semibold leading-tight text-slate-400">
                                            Tidak ada data return untuk produk ini
                                        </span>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Stock Update Modal --}}
    @if($showStockUpdateModal)
        <div class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full sm:p-6">
                    <div>
                        <div class="mt-3 sm:mt-0">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Update Stok Produk</h3>

                            {{-- Product Search --}}
                            <div class="relative mb-4" x-data="{ open: false }" @click.away="open = false">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cari & Pilih Produk</label>
                                <input type="text" wire:model.debounce.300ms="stockUpdateSearch"
                                    @focus="open = true" @input="open = true"
                                    class="text-sm ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"
                                    placeholder="Ketik nama atau kode produk..." />

                                {{-- Search Results Dropdown --}}
                                @if(strlen($stockUpdateSearch) >= 2)
                                    <div x-show="open" class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                                        @forelse($this->stockUpdateSearchResults as $product)
                                            <button type="button"
                                                wire:click="addStockUpdateProduct({{ $product->id }})"
                                                @click="open = false"
                                                class="w-full text-left px-4 py-2.5 text-sm hover:bg-blue-50 transition-colors flex justify-between items-center border-b border-gray-100 last:border-b-0">
                                                <div>
                                                    <span class="font-semibold text-gray-800">{{ $product->name }}</span>
                                                    <span class="text-gray-400 text-xs ml-2">({{ $product->kode }})</span>
                                                </div>
                                                <span class="text-xs text-gray-500">Stok: {{ $product->stok }}</span>
                                            </button>
                                        @empty
                                            <div class="px-4 py-3 text-sm text-gray-500 text-center">Produk tidak ditemukan</div>
                                        @endforelse
                                    </div>
                                @endif
                            </div>

                            {{-- Selected Products Table --}}
                            @if(count($stockUpdateItems) > 0)
                                <div class="mb-4 border rounded-lg overflow-hidden overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-3 py-2 text-left text-xs font-bold uppercase text-gray-500">Produk</th>
                                                <th class="px-3 py-2 text-center text-xs font-bold uppercase text-gray-500">Stok</th>
                                                <th class="px-3 py-2 text-center text-xs font-bold uppercase text-gray-500">Harga Beli Saat Ini</th>
                                                <th class="px-3 py-2 text-center text-xs font-bold uppercase text-gray-500">Qty Tambah</th>
                                                <th class="px-3 py-2 text-center text-xs font-bold uppercase text-gray-500">Harga Beli Baru</th>
                                                <th class="px-3 py-2 text-center text-xs font-bold uppercase text-gray-500">Harga Rata-rata</th>
                                                <th class="px-3 py-2 text-center text-xs font-bold uppercase text-gray-500"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($stockUpdateItems as $index => $suItem)
                                                @php
                                                    $curStock = $suItem['current_stock'] ?? 0;
                                                    $curPrice = $suItem['current_price'] ?? 0;
                                                    $qtyAdd = (int)($suItem['qty_added'] ?? 0);
                                                    $newPrice = (int) preg_replace("/[^0-9]/", "", $suItem['purchase_price'] ?? '0');
                                                    $totalStock = $curStock + $qtyAdd;
                                                    $avgPrice = $totalStock > 0 ? round((($curStock * $curPrice) + ($qtyAdd * $newPrice)) / $totalStock) : 0;
                                                @endphp
                                                <tr wire:key="su-item-{{ $index }}" class="border-t">
                                                    <td class="px-3 py-2">
                                                        <span class="font-semibold text-gray-800 text-xs">{{ $suItem['product_name'] }}</span>
                                                    </td>
                                                    <td class="px-3 py-2 text-center text-gray-600 text-xs">{{ $curStock }}</td>
                                                    <td class="px-3 py-2 text-center text-gray-600 text-xs">Rp {{ number_format($curPrice) }}</td>
                                                    <td class="px-3 py-2 text-center">
                                                        <input type="number" min="1"
                                                            wire:model.defer="stockUpdateItems.{{ $index }}.qty_added"
                                                            class="w-16 text-center text-sm rounded-lg border border-gray-300 px-1.5 py-1 focus:border-blue-500 focus:outline-none" />
                                                        @error("stockUpdateItems.{$index}.qty_added")
                                                            <p class="text-red-500 text-xxs mt-0.5">{{ $message }}</p>
                                                        @enderror
                                                    </td>
                                                    <td class="px-3 py-2 text-center">
                                                        <input type="text"
                                                            wire:model.defer="stockUpdateItems.{{ $index }}.purchase_price"
                                                            class="w-28 text-center text-sm rounded-lg border border-gray-300 px-1.5 py-1 focus:border-blue-500 focus:outline-none"
                                                            placeholder="Rp"
                                                            x-data="{
                                                                formatNumber(e) {
                                                                    let val = e.target.value.replace(/\D/g, '');
                                                                    e.target.value = val ? new Intl.NumberFormat('id-ID').format(val) : '';
                                                                }
                                                            }" x-on:input="formatNumber($event)" />
                                                        @error("stockUpdateItems.{$index}.purchase_price")
                                                            <p class="text-red-500 text-xxs mt-0.5">{{ $message }}</p>
                                                        @enderror
                                                    </td>
                                                    <td class="px-3 py-2 text-center">
                                                        <span class="font-semibold text-blue-600 text-xs">Rp {{ number_format($avgPrice) }}</span>
                                                    </td>
                                                    <td class="px-3 py-2 text-center">
                                                        <button wire:click="removeStockUpdateProduct({{ $index }})"
                                                            class="text-red-500 hover:text-red-700">
                                                            <i class="fas fa-trash-alt text-xs"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="mb-4 border-2 border-dashed border-gray-200 rounded-lg p-6 text-center">
                                    <i class="fas fa-box-open text-3xl text-gray-300 mb-2"></i>
                                    <p class="text-sm text-gray-400">Belum ada produk dipilih. Cari dan pilih produk di atas.</p>
                                </div>
                            @endif
                            @error('stockUpdateItems')
                                <p class="text-red-500 text-xs mb-3 -mt-2">{{ $message }}</p>
                            @enderror

                            {{-- Upload Nota --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Upload Nota <span class="text-red-500">*</span></label>
                                <input type="file" wire:model="stockUpdateNota" accept="image/*"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all" />
                                <div wire:loading wire:target="stockUpdateNota" class="mt-2 flex items-center gap-2 text-sm text-gray-500">
                                    <div class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-solid border-blue-500 border-r-transparent" role="status"></div>
                                    Mengupload...
                                </div>
                                @if($stockUpdateNota)
                                    <div class="mt-2">
                                        <img src="{{ $stockUpdateNota->temporaryUrl() }}" class="h-32 rounded-lg border object-cover" alt="Preview nota" />
                                    </div>
                                @endif
                                @error('stockUpdateNota')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Notes --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                                <textarea wire:model.defer="stockUpdateNotes" rows="2"
                                    class="text-sm ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"
                                    placeholder="Contoh: Restock dari supplier X"></textarea>
                            </div>

                            {{-- Buttons --}}
                            <div class="flex gap-x-2">
                                <button type="button" wire:click='closeStockUpdateModal'
                                    class="flex w-full justify-center gap-x-2 items-center rounded-lg bg-gray-600 px-6 py-2.5 text-xs font-medium uppercase leading-normal text-white transition duration-150 ease-in-out hover:bg-gray-700">
                                    <div wire:loading wire:target='closeStockUpdateModal'>
                                        <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent" role="status"></div>
                                    </div>
                                    Batal
                                </button>
                                <button type="button" wire:click='submitStockUpdate' wire:loading.attr="disabled"
                                    class="flex w-full justify-center gap-x-2 items-center rounded-lg bg-green-600 px-6 py-2.5 text-xs font-medium uppercase leading-normal text-white transition duration-150 ease-in-out hover:bg-green-700 disabled:opacity-50">
                                    <div wire:loading wire:target='submitStockUpdate'>
                                        <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent" role="status"></div>
                                    </div>
                                    <span wire:loading.remove wire:target="submitStockUpdate">Simpan Update Stok</span>
                                    <span wire:loading wire:target="submitStockUpdate">Menyimpan...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            @this.on('triggerDelete', id => {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    html: "Data ini tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Ya, Hapus'
                }).then((result) => {
                    if (result.value) {
                        @this.call('delete', id)
                    }
                });
            });

            @this.on('triggerRestoreReturn', (id, qty) => {
                Swal.fire({
                    title: 'Kembalikan ke Stok?',
                    html: `<b>${qty}</b> unit akan dikembalikan ke stok inventory.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#16a34a',
                    confirmButtonText: 'Ya, Kembalikan',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.value) {
                        @this.call('restoreReturnToStock', id)
                    }
                });
            });

            @this.on('triggerDeleteReturn', id => {
                Swal.fire({
                    title: 'Hapus Data Return?',
                    html: "Data return akan dihapus tanpa mengembalikan stok.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.value) {
                        @this.call('deleteReturn', id)
                    }
                });
            });
        })
    </script>
</div>
