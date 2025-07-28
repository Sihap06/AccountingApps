<div class="flex flex-wrap -mx-3 custom-height" wire:init="loadProducts">
    <div class="flex-none w-full max-w-full px-3 h-full">
        <div
            class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border h-full">
            <div
                class="block md:flex w-full justify-between items-center p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <button wire:click='openModal' type="button"
                    class="px-8 py-2 text-xs font-bold leading-normal text-center text-white capitalize transition-all ease-in rounded-lg shadow-md bg-slate-700 bg-150 hover:shadow-xs hover:-translate-y-px flex items-center gap-x-2">
                    <div wire:loading wire:target='openModal'>
                        <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                            role="status">
                            <span
                                class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                        </div>
                    </div>
                    <span>New Inventory</span>
                </button>
                <div class="flex w-full md:w-4/12 items-center gap-x-3">
                    <input type="text" wire:model.debounce.500ms="searchTerm"
                        class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"
                        placeholder="Masukkan nama atau kode" />
                </div>
            </div>

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
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                                @if (count($data) == 0)
                                    <tr>
                                        <td colspan="7"
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
                                {{ $action === 'add' ? 'Add New Inventory' : 'Edit Inventory' }}
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

                                    <div class="flex gap-x-2">
                                        <button type="button" wire:click='closeModal'
                                            class="flex w-full justify-center gap-x-2 items-center rounded bg-gray-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-gray-700 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-gray-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-gray-800 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">
                                            <div wire:loading wire:target='closeModal'>
                                                <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                                    role="status">
                                                    <span
                                                        class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                                                </div>
                                            </div>
                                            Kembali
                                        </button>

                                        <x-ui.button type="submit" title="Submit" color="primary" wireLoading
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
                            Product Returns
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
                                    <th class="px-4 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Date
                                    </th>
                                    <th class="px-4 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Transaction Order
                                    </th>
                                    <th class="px-4 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Quantity
                                    </th>
                                    <th class="px-4 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Return Reason
                                    </th>
                                    <th class="px-4 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Returned By
                                    </th>
                                    <th class="px-4 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Notes
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($productReturns as $return)
                                <tr>
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
                                    <td colspan="6" class="p-4 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <span class="text-xs font-semibold leading-tight text-slate-400">
                                            No returns found for this product
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            @this.on('triggerDelete', id => {
                Swal.fire({
                    title: 'Are You Sure?',
                    html: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                }).then((result) => {
                    if (result.value) {
                        @this.call('delete', id)
                    }
                });
            });
        })
    </script>
</div>
