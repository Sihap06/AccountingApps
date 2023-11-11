<div
    class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border h-full w-full">
    <div class="{{ $isEdit ? 'hidden' : 'block' }}">
        <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
            <h6 class="dark:text-white">Transaction Table</h6>
        </div>
        <div class="flex-auto p-6">
            <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
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
                                Service
                            </th>
                            <th
                                class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                Biaya
                            </th>
                            <th
                                class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                Modal
                            </th>
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
                        @foreach ($data as $item)
                            <tr wire:loading.remove wire:target='gotoPage, previousPage, nextPage'>
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
                                        {{ $item->service }}
                                    </span>
                                </td>
                                <td
                                    class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                    <span
                                        class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                        Rp {{ number_format($item->biaya) }}
                                    </span>
                                </td>
                                <td
                                    class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                    <span
                                        class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                        Rp {{ number_format($item->modal) }}
                                    </span>
                                </td>
                                <td
                                    class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                    <span
                                        class="text-xs font-semibold uppercase leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                        {{ $item->payment_method }}
                                    </span>
                                </td>
                                <td
                                    class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                    <div>
                                        <button wire:click='edit({{ $item->id }})'
                                            class="inline-block px-3 py-2 text-xs mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-primary leading-normal  ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md">
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
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        @for ($i = 0; $i <= 10; $i++)
                            <tr wire:loading.class="table-row" class="hidden" wire:loading.class.remove="hidden"
                                wire:target='gotoPage, previousPage, nextPage'>
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
                <div class="mt-4 px-4">
                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </div>


    <div class="p-6 flex justify-center w-full {{ $isEdit ? 'block' : 'hidden' }}">
        <div class="w-6/12">
            <div class="text-center my-6">
                <h5 class="dark:text-white">Update Transaction</h5>
            </div>
            <form wire:submit.prevent="update">
                <div class="flex flex-wrap -mx-3">
                    <div class="mb-8 w-full max-w-full px-3 shrink-0 md:flex-0">
                        <div wire:ignore>
                            <x-ui.input label="Order ID" wire:model="order_transaction" id="order_transaction" />
                        </div>
                        @error('order_transaction')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-8 w-full max-w-full px-3 shrink-0 md:flex-0">
                        <div wire:ignore>
                            <x-ui.input label="Service" wire:model="service" id="service" />
                        </div>
                        @error('service')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-8 w-full max-w-full px-3 shrink-0 md:flex-0">
                        <div wire:ignore>
                            <x-ui.input label="Biaya" wire:model="biaya" id="biaya" x-data="{
                                formatNumber: function(event) {
                                    const input = event.target;
                                    const value = input.value.replace(/\D/g, ''); // Remove non-numeric characters
                                    input.value = new Intl.NumberFormat('en-US').format(value);
                                }
                            }"
                                x-on:input="formatNumber($event)" />
                        </div>
                        @error('biaya')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-8 w-full max-w-full px-3 shrink-0 md:flex-0">
                        <div wire:ignore>
                            <x-ui.select label="Teknisi" wire:model="technical_id" id="teknisi" search size="lg"
                                disabled>
                                <option value=""></option>
                                @foreach ($technician as $value)
                                    <option value="{{ $value->id }}">{{ $value->name }}
                                    </option>
                                @endforeach
                            </x-ui.select>
                        </div>
                        @error('technical_id')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-8 w-full max-w-full px-3 shrink-0 md:flex-0">
                        <div wire:ignore>
                            <x-ui.select label="Sparepart" wire:model="product_id" id="sparepart" search size="lg"
                                disabled>
                                <option value=""></option>
                                @foreach ($inventory as $index => $value)
                                    <option value="{{ $index }}">{{ $value }}
                                    </option>
                                @endforeach
                            </x-ui.select>
                        </div>
                        @error('product_id')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-8 w-full max-w-full px-3 shrink-0 md:flex-0">
                        <div wire:ignore>
                            <x-ui.select label="Metode Pembayaran" wire:model="payment_method" id="metode_pembayaran"
                                size="lg">
                                <option value=""></option>
                                <option value="bca">BCA</option>
                                <option value="mandiri">MANDIRI</option>
                                <option value="transfer">TRANSFER</option>
                                <option value="debit">DEBIT</option>
                                <option value="qris">QRIS</option>
                                <option value="cash">CASH</option>
                            </x-ui.select>
                        </div>
                        @error('payment_method')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-8 w-full max-w-full px-3 shrink-0 md:flex gap-x-4">
                        <button type="button" wire:click='batal'
                            class="px-8 py-2 mb-4 text-xs font-bold leading-normal text-center text-black capitalize transition-all ease-in rounded-lg shadow-md bg-slate-50 bg-150 hover:shadow-xs hover:-translate-y-px w-full">
                            <div wire:loading wire:target='batal'>
                                <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                    role="status">
                                    <span
                                        class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                                </div>
                            </div>
                            Batal
                        </button>
                        <button type="submit"
                            class="px-8 py-2 mb-4 text-xs font-bold leading-normal text-center text-white capitalize transition-all ease-in rounded-lg shadow-md bg-slate-700 bg-150 hover:shadow-xs hover:-translate-y-px w-full">
                            <div wire:loading wire:target='update'>
                                <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                    role="status">
                                    <span
                                        class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                                </div>
                            </div>
                            Submit
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
