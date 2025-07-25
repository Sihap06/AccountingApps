<div
    class="flex w-full gap-x-4 h-full min-w-0 break-words border-0 border-transparent border-solid shadow-xl  dark:shadow-dark-xl rounded-2xl">
    <div class="relative flex flex-col bg-white  {{ $isAdd || $isEdit ? 'w-8/12' : 'w-full' }}">
        <div class="flex flex-col md:flex-row justify-between p-6 pb-0">
            <h6 class="dark:text-white">Expenditure Table</h6>
            <div class="w-3/12 text-slate-900 font-bold text-right">
                <span>Rp {{ number_format($totalAmount) }}</span>
            </div>
        </div>
        <div class="my-4 px-6 flex items-center justify-between">
            <div class="grid grid-cols-2 gap-x-4 items-center w-full md:w-6/12">
                <div class="w-full items-center">
                    <select wire:model.debounce.500ms="selectedMonth"
                        class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                        @foreach ($listMonth as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full items-center">
                    <select wire:model.debounce.500ms="selectedYear"
                        class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                        @foreach ($listYear as $item)
                            <option value="{{ $item }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="w-3/12 text-right">
                <button type="button" wire:click='setShowAdd'
                    class="px-8 py-2 mb-4 text-xs font-bold leading-normal text-center text-white capitalize transition-all ease-in rounded-lg shadow-md bg-slate-700 bg-150 hover:shadow-xs hover:-translate-y-px">
                    <div wire:loading wire:target='setShowAdd'>
                        <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                            role="status">
                            <span
                                class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                        </div>
                    </div>
                    {{ $isAdd || $isEdit ? 'Batal' : 'Tambah' }}
                </button>
            </div>
        </div>
        <div class="flex-auto p-6">
            <div class="p-0 overflow-y-auto h-sidenav">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                    <thead class="align-bottom">
                        <tr>
                            <th
                                class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                Tanggal
                            </th>
                            <th
                                class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                Jenis
                            </th>
                            <th
                                class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                Total
                            </th>
                            <th
                                class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $index => $item)
                            <tr wire:key='{{ $index }}' wire:loading.remove
                                wire:target='selectedMonth, selectedYear'>
                                <td
                                    class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                    <span
                                        class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td
                                    class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                    <span
                                        class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                        {{ $item->jenis }}
                                    </span>
                                </td>
                                <td
                                    class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                    <span
                                        class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                        Rp {{ number_format($item->total) }}
                                    </span>
                                </td>
                                <td
                                    class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                    <button @if ($isAdd) disabled @endif type="button"
                                        wire:click='edit({{ $item->id }})'
                                        class="inline-block px-3 py-2 text-xs mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-primary leading-normal  ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md disabled:cursor-not-allowed">
                                        <i class="fas fa-edit" wire:loading.remove
                                            wire:target='edit({{ $item->id }})'></i>
                                        <div wire:loading wire:target='edit({{ $item->id }})'>
                                            <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                                role="status">
                                                <span
                                                    class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                                            </div>
                                        </div>
                                    </button>

                                    <form wire:submit.prevent='delete({{ $item->id }})' id="formDelete" hidden>
                                        <input type="submit" value="Submit" hidden>
                                    </form>

                                </td>
                            </tr>
                        @endforeach
                        @for ($i = 0; $i <= 10; $i++)
                            <tr wire:loading.class="table-row" class="hidden" wire:loading.class.remove="hidden"
                                wire:target='selectedMonth, selectedYear'>
                                <td
                                    class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                    <div class="mb-2 h-5 w-full rounded overflow-hidden relative bg-gray-200" />
                                </td>
                                <td
                                    class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                    <div class="mb-2 h-5 w-full rounded overflow-hidden relative bg-gray-200" />
                                </td>
                                <td
                                    class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                    <div class="mb-2 h-5 w-full rounded overflow-hidden relative bg-gray-200" />
                                </td>
                                <td
                                    class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                    <div class="mb-2 h-5 w-full rounded overflow-hidden relative bg-gray-200" />
                                </td>
                            </tr>
                        @endfor

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @if ($isAdd || $isEdit)
        <div
            class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl h-full w-4/12 transition-all">
            <div
                class="flex justify-between p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <h6 class="dark:text-white">{{ $isAdd ? 'Add' : 'Edit' }} Expenditure</h6>
            </div>

            <div class="w-full p-6">
                <form wire:submit.prevent="{{ $isAdd ? 'store' : 'update' }}">
                    <div class="relative mb-8">
                        <x-ui.input-default wire:model="tanggal" label="Tanggal" type="date" />

                        @error('tanggal')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                        @enderror

                    </div>
                    <div class="relative mb-8">
                        <x-ui.input-default wire:model="jenis" label="Jenis" />
                        @error('jenis')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="relative mb-8">
                        <x-ui.input-default wire:model="total" id="total" label="Total" x-data="{
                            formatNumber: function(event) {
                                const input = event.target;
                                const value = input.value.replace(/\D/g, ''); // Remove non-numeric characters
                                input.value = new Intl.NumberFormat('en-US').format(value);
                            }
                        }"
                            x-on:input="formatNumber($event)" />
                        @error('total')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <x-ui.button type="submit" title="Submit" color="primary" wireLoading
                        formAction="{{ $isAdd ? 'store' : 'update' }}" />
                </form>
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
                                Alasan Perubahan
                            </h3>
                            <div class="flex-auto px-0 pt-0 pb-2">
                                <form wire:submit.prevent="submitReason">
                                    <div class="relative mb-8 mt-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">
                                            Masukkan alasan perubahan <span class="text-red-500">*</span>
                                        </label>
                                        <textarea wire:model="reason" rows="4"
                                            class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"
                                            placeholder="Jelaskan mengapa perubahan ini diperlukan..."></textarea>
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
</div>
