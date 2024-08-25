<div>
    @if ($isEdit)
        @livewire('dashboard.update-transaction', ['id' => $selectedId])
    @else
        <div
            class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl w-full custom-height-transaction">
            <div class="flex flex-col md:flex-row justify-between p-6 pb-0 ">
                <h6 class="dark:text-white">Transaction Table</h6>
                <div class="w-3/12 text-slate-900 font-bold">
                    <div class="flex justify-between mb-2">
                        <span>Omset</span>
                        <span>Rp {{ number_format($totalBiaya) }}</span>
                    </div>
                </div>
            </div>
            <div class="my-4 px-6 flex items-center justify-between">
                <div class="flex gap-x-4 items-center w-full">
                    <p class="mb-0">Filter :</p>
                    <div class="flex w-full md:w-3/12 items-center">
                        <input type="date" wire:model.debounce.500ms="selectedDate"
                            class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                    </div>
                    <div class="flex w-full md:w-4/12 items-center">
                        <select wire:model.debounce.500ms="selectedPaymentMethod"
                            class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                            <option value="">Semua Metode Pembayaran</option>
                            <option value="cash">CASH</option>
                            <option value="qris">QRIS</option>
                            <option value="bca">BCA</option>
                            <option value="debit">DEBIT</option>
                            <option value="mandiri">MANDIRI</option>
                            <option value="transfer">TRANSFER</option>
                            <option value="kartu kredit">KARTU KREDIT</option>
                        </select>
                    </div>
                </div>

                <div class="flex w-full md:w-5/12 items-center">
                    <input type="text" wire:model.debounce.500ms="searchTerm"
                        class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"
                        placeholder="Masukkan order id atau nama customer" />
                </div>
            </div>
            <div class="flex-auto p-6">
                <div class="p-0 overflow-y-auto h-sidenav">
                    <table
                        class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                        <thead class="align-bottom">
                            <tr>
                                <th
                                    class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Tanggal
                                </th>
                                <th
                                    class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Order ID
                                </th>
                                <th
                                    class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Customer
                                </th>
                                <th
                                    class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Biaya
                                </th>
                                @if (auth()->user()->role !== 'sysadmin')
                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Modal
                                    </th>
                                @endif
                                <th
                                    class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Metode Pembayaran
                                </th>
                                <th
                                    class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $index => $item)
                                <tr wire:key='{{ $item->created_at . time() }}' wire:loading.remove
                                    wire:target='gotoPage, previousPage, nextPage, searchTerm, selectedDate, selectedPaymentMethod'>
                                    <td
                                        class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span
                                            class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                            {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td
                                        class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span
                                            class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                            {{ $item->order_transaction }}
                                        </span>
                                    </td>
                                    <td
                                        class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span
                                            class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                            {{ $item->customer_name }}
                                        </span>
                                    </td>
                                    <td
                                        class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span
                                            class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                            Rp
                                            {{ number_format($item->first_item_biaya + $item->other_items_biaya) }}
                                        </span>
                                    </td>
                                    @if (auth()->user()->role !== 'sysadmin')
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                Rp
                                                {{ number_format($item->first_item_modal + $item->other_items_modal) }}
                                            </span>
                                        </td>
                                    @endif
                                    <td
                                        class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span
                                            class="text-xs font-semibold uppercase leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                            {{ $item->payment_method }}
                                        </span>
                                    </td>
                                    <td
                                        class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <div class="flex flex-row gap-x-3">
                                            <button wire:click='edit("{{ $item->id }}")'
                                                class="inline-block px-3 py-2 text-xs mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-primary leading-normal  ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md">
                                                <i class="fas fa-edit" wire:loading.remove
                                                    wire:target='edit("{{ $item->id }}")'></i>
                                                <div wire:loading wire:target='edit("{{ $item->id }}")'>
                                                    <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                                        role="status">
                                                        <span
                                                            class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                                                    </div>
                                                </div>
                                            </button>
                                            @if (auth()->user()->role === 'master_admin')
                                                <button wire:click="$emit('triggerDelete',{{ $item->id }})"
                                                    class="inline-block px-3 py-2 text-xs mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-red-600 leading-normal  ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md">
                                                    <i class="fas fa-trash" wire:loading.remove
                                                        wire:target='delete("{{ $item->id }}")'></i>
                                                    <div wire:loading wire:target='delete("{{ $item->id }}")'>
                                                        <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                                            role="status">
                                                            <span
                                                                class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                                                        </div>
                                                    </div>
                                                </button>
                                            @endif

                                            <button type="button"
                                                wire:click="$emit('triggerComplaint',{{ $item->id }})"
                                                class="px-3 py-2 text-xs mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-red-600 leading-normal  ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md flex gap-x-2 items-center">
                                                <div wire:loading wire:target='complaint("{{ $item->id }}")'>
                                                    <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                                        role="status">
                                                        <span
                                                            class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                                                    </div>
                                                </div>
                                                <span>Complaint</span>

                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            @for ($i = 0; $i <= 10; $i++)
                                <tr wire:loading.class="table-row" class="hidden" wire:loading.class.remove="hidden"
                                    wire:target='searchTerm, selectedDate, selectedPaymentMethod'>
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
                                    <td
                                        class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <div class="mb-2 h-5 w-full rounded overflow-hidden relative bg-gray-200" />
                                    </td>
                                    @if (auth()->user()->role !== 'sysadmin')
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <div class="mb-2 h-5 w-full rounded overflow-hidden relative bg-gray-200" />
                                        </td>
                                    @endif
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
    @endif
</div>

@push('style')
    <style>
        .custom-height-transaction {
            height: calc(100vh - 155px);
        }

        .table-height-pos {
            height: calc(100vh - 400px);
        }
    </style>
@endpush

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            @this.on('triggerComplaint', id => {
                Swal.fire({
                    title: 'Confirm Complaint This Transaction?',
                    html: "",
                    icon: 'warning',
                    showCancelButton: true,
                }).then((result) => {
                    if (result.value) {
                        @this.call('complaint', id)
                    }
                });
            });

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
@endpush
