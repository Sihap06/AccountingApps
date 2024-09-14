<div class="flex flex-row w-full gap-x-4 justify-center mb-4">
    <div class="{{ $formAction === '' ? 'w-full' : 'w-8/12' }}">
        <div
            class=" border-black/12.5 dark:bg-slate-850 dark:shadow-dark-xl shadow-xl z-20 min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border items-center flex-1">
            <div class="border-black/12.5 rounded-t-2xl border-b-0 border-solid p-6 pb-0">
                <div class="flex justify-between items-start">
                    <p class="mb-0 dark:text-white/80">Update Transaction</p>
                    <div class="flex flex-col gap-2">
                        <p>ORDER ID : {{ $transaction['order_transaction'] }}</p>
                        <x-ui.button type="button" title="Add Service Items" color="primary" wireLoading
                            formAction="$set('formAction', 'add')" />
                    </div>
                </div>
            </div>
            <div class="p-6">
                <form wire:submit.prevent="updateTransaction">
                    <div class="flex flex-col justify-between -mx-3">
                        <div class="mb-8">
                            <div class="w-full max-w-full px-3 shrink-0 md:flex-0">
                                <div wire:ignore>
                                    <x-ui.select label="Customer" wire:model="customer_id" id="customer_id" search
                                        size="lg">
                                        @foreach ($customers as $value)
                                            <option value="{{ $value->id }}">{{ $value->no_telp }}
                                                ({{ $value->name }})
                                            </option>
                                        @endforeach
                                    </x-ui.select>
                                </div>
                                @error('customer_id')
                                    <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="flex flex-row justify-between items-center gap-x-2">
                            <div class="mb-8 w-full">
                                <div class="w-full max-w-full px-3 shrink-0 md:flex-0">
                                    <div wire:ignore>
                                        <x-ui.select label="Metode Pembayaran" wire:model="paymentMethod"
                                            id="paymentMethod" search size="lg">
                                            <option value="cash">CASH</option>
                                            <option value="qris">QRIS</option>
                                            <option value="bca">BCA</option>
                                            <option value="debit">DEBIT</option>
                                            <option value="mandiri">MANDIRI</option>
                                            <option value="transfer">TRANSFER</option>
                                        </x-ui.select>
                                    </div>
                                    @error('paymentMethod')
                                        <div class="text-red-500 text-sm">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-8 w-full">
                                <div class="w-full max-w-full px-3 shrink-0 md:flex-0">
                                    <input type="date" wire:model.defer="orderDate"
                                        class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-3 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                                    @error('orderDate')
                                        <div class="text-red-500 text-sm">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex-auto px-0 pt-0 mt-4 pb-4 overflow-auto table-height-pos">
                            <div class="p-0">
                                <table
                                    class="w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                                    <thead class="align-bottom">
                                        <tr>
                                            <th
                                                class="text-left py-3 px-2 font-bold uppercase bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                Service</th>
                                            <th
                                                class="text-left py-3 px-2 font-bold uppercase bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                Biaya</th>
                                            <th
                                                class="text-left py-3 px-2 font-bold uppercase bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                Teknisi</th>
                                            <th
                                                class="text-left py-3 px-2 font-bold uppercase bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                Sparepart</th>
                                            <th
                                                class="text-left py-3 px-2 font-bold uppercase bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                Garansi</th>
                                            <th
                                                class="text-left py-3 px-2 font-bold uppercase bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr wire:key='{{ $transaction['order_transaction'] . time() }}'
                                            wire:loading.remove
                                            wire:target='gotoPage, previousPage, nextPage, searchTerm'>

                                            <td
                                                class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                    {{ $transaction['service'] }}
                                                </span>
                                            </td>
                                            <td
                                                class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                    {{ number_format($transaction['biaya']) }}
                                                </span>
                                            </td>
                                            <td
                                                class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                    {{ $transaction['technical_id'] !== null ? \App\Models\Technician::findOrFail($transaction['technical_id'])->name : '-' }}
                                                </span>
                                            </td>
                                            <td
                                                class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                    {{ $transaction['product_id'] !== null ? \App\Models\Product::findOrFail($transaction['product_id'])->name : '-' }}
                                                </span>
                                            </td>
                                            <td
                                                class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <span
                                                    class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                    @if ($transaction['warranty'] != '')
                                                        {{ $transaction['warranty'] }}
                                                        @if ($transaction['warranty_type'] == 'daily')
                                                            Hari
                                                        @elseif ($transaction['warranty_type'] == 'weekly')
                                                            Minggu
                                                        @else
                                                            Bulan
                                                        @endif
                                                    @else
                                                        -
                                                    @endif

                                                </span>
                                            </td>
                                            <td
                                                class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                <div class="flex gap-x-4">
                                                    <button type="button"
                                                        wire:click="editItemTransaction({{ $transaction['id'] }})"
                                                        class="inline-block px-3 py-2 text-xs mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-sky-500 leading-normal ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md">
                                                        <i class="fas fa-edit" wire:loading.remove
                                                            wire:target='editItemTransaction({{ $transaction['id'] }})'></i>

                                                        <div wire:loading
                                                            wire:target='editItemTransaction({{ $transaction['id'] }})'>
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
                                        @foreach ($transaction_items as $item)
                                            <tr wire:key='{{ $item->id . time() }}' wire:loading.remove
                                                wire:target='gotoPage, previousPage, nextPage, searchTerm'>

                                                <td
                                                    class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span
                                                        class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                        {{ $item['service'] }}
                                                    </span>
                                                </td>
                                                <td
                                                    class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span
                                                        class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                        {{ number_format($item['biaya']) }}
                                                    </span>
                                                </td>
                                                <td
                                                    class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span
                                                        class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                        {{ $item['technical_id'] !== null ? \App\Models\Technician::findOrFail($item['technical_id'])->name : '-' }}
                                                    </span>
                                                </td>
                                                <td
                                                    class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span
                                                        class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                        {{ $item['product_id'] !== null ? \App\Models\Product::findOrFail($item['product_id'])->name : '-' }}
                                                    </span>
                                                </td>
                                                <td
                                                    class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span
                                                        class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                        @if ($item['warranty'] != '')
                                                            {{ $item['warranty'] }}
                                                            @if ($item['warranty_type'] == 'daily')
                                                                Hari
                                                            @elseif ($item['warranty_type'] == 'weekly')
                                                                Minggu
                                                            @else
                                                                Bulan
                                                            @endif
                                                        @else
                                                            -
                                                        @endif

                                                    </span>
                                                </td>
                                                <td
                                                    class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <div class="flex gap-x-2">
                                                        <button type="button"
                                                            wire:click="editItem({{ $item['id'] }})"
                                                            class="inline-block px-3 py-2 text-xs mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-sky-500 leading-normal ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md">
                                                            <i class="fas fa-edit" wire:loading.remove
                                                                wire:target='editItem({{ $item['id'] }})'></i>

                                                            <div wire:loading
                                                                wire:target='editItem({{ $item['id'] }})'>
                                                                <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                                                    role="status">
                                                                    <span
                                                                        class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                                                                </div>
                                                            </div>
                                                        </button>
                                                        <button type="button"
                                                            wire:click="removeItem({{ $item['id'] }})"
                                                            class="inline-block px-3 py-2 text-xs mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-red-600 leading-normal ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md">
                                                            <i class="fas fa-trash-alt" wire:loading.remove
                                                                wire:target='removeItem({{ $item['id'] }})'></i>

                                                            <div wire:loading
                                                                wire:target='removeItem({{ $item['id'] }})'>
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
                                        @foreach ($serviceItems as $index => $item)
                                            <tr wire:key='{{ $index }}' wire:loading.remove
                                                wire:target='gotoPage, previousPage, nextPage, searchTerm'>

                                                <td
                                                    class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span
                                                        class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                        {{ $item['service'] }}
                                                    </span>
                                                </td>
                                                <td
                                                    class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span
                                                        class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                        {{ number_format($item['biaya']) }}
                                                    </span>
                                                </td>
                                                <td
                                                    class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span
                                                        class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                        {{ $item['technical_name'] !== '' ? $item['technical_name'] : '-' }}
                                                    </span>
                                                </td>
                                                <td
                                                    class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span
                                                        class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                        {{ $item['product_name'] !== '' ? $item['product_name'] : '-' }}
                                                    </span>
                                                </td>
                                                <td
                                                    class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <div>
                                                        <button type="button"
                                                            wire:click="removeServiceItem({{ $index }})"
                                                            class="inline-block px-3 py-2 text-xs mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-red-600 leading-normal ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md">
                                                            <i class="fas fa-trash-alt" wire:loading.remove
                                                                wire:target='removeServiceItem({{ $index }})'></i>

                                                            <div wire:loading
                                                                wire:target='removeServiceItem({{ $index }})'>
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
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="flex flex-row gap-x-2 items-center mt-4">
                            <div class="w-full max-w-full px-3">
                                <x-ui.button type="button" title="Back" color="danger" wireLoading
                                    formAction="updateIsEdit" />
                            </div>
                            <div class="w-full max-w-full px-3">
                                <x-ui.button type="submit" title="Update Transaction" color="primary" wireLoading
                                    formAction="updateTransaction" />
                            </div>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
    @if ($formAction === 'add')
        <div
            class=" border-black/12.5 dark:bg-slate-850 dark:shadow-dark-xl shadow-xl z-20 min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border items-center flex-1">
            <div class="border-black/12.5 rounded-t-2xl border-b-0 border-solid p-6 pb-0">
                <div class="flex justify-between items-center">
                    <p class="mb-0 dark:text-white/80">
                        Add Service Items
                    </p>
                    <button type="button" wire:loading.remove='$set("formAction", "")' type="button"
                        wire:click='$set("formAction", "")'>
                        <svg width="15px" height="15px" viewBox="-0.5 0 25 25" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 21.32L21 3.32001" stroke="#000000" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M3 3.32001L21 21.32" stroke="#000000" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </button>

                    <div wire:loading wire:target='$set("formAction", "")'>
                        <div class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                            role="status">
                            <span
                                class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-6 mt-4">
                <form wire:key='{{ time() }}' wire:submit.prevent="addServiceItem" class="h-full">
                    <div class="flex flex-col justify-between h-full -mx-3">
                        <div>
                            <div class="mb-8 w-full max-w-full px-3 shrink-0 md:flex-0">
                                <label for="service" class="text-sm">Service</label>
                                <input type="text" wire:model.lazy='service' id="service"
                                    class="relative w-full bg-white border border-gray-300 rounded-lg pl-3 pr-10 py-2 text-left focus:ring-1 focus:outline-0 focus:ring-primary focus:border-blue-500">
                                @error('service')
                                    <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-8 w-full max-w-full px-3 shrink-0 md:flex-0">
                                <label for="biaya" class="text-sm">Biaya</label>
                                <input type="text" wire:model.lazy='biaya' id="biaya" x-data="{
                                    formatNumber: function(event) {
                                        const input = event.target;
                                        const value = input.value.replace(/\D/g, ''); // Remove non-numeric characters
                                        input.value = new Intl.NumberFormat('en-US').format(value);
                                    }
                                }"
                                    x-on:input="formatNumber($event)"
                                    class="relative w-full bg-white border border-gray-300 rounded-lg pl-3 pr-10 py-2 text-left focus:ring-1 focus:outline-0 focus:ring-primary focus:border-blue-500">
                                @error('biaya')
                                    <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-8 w-full max-w-full px-3 shrink-0 md:flex-0">
                                @livewire('searchable-select', ['list' => $technician, 'selectedOption' => $technical, 'name' => 'technical', 'label' => 'Teknisi'])

                                @error('technical')
                                    <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-8 w-full max-w-full px-3 shrink-0 md:flex-0">
                                @livewire('searchable-select', ['list' => $inventory, 'selectedOption' => $product, 'name' => 'product', 'label' => 'Sparepart'])
                                @error('product')
                                    <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4 w-full max-w-full px-3 shrink-0 md:flex-0">
                                <label for="warranty" class="text-sm">Garansi</label>
                                <div class="flex flex-row gap-2">
                                    <input type="text" wire:model.lazy='warranty' id="warranty"
                                        x-data="{
                                            formatNumber: function(event) {
                                                const input = event.target;
                                                const value = input.value.replace(/\D/g, ''); // Remove non-numeric characters
                                                input.value = new Intl.NumberFormat('en-US').format(value);
                                            }
                                        }" x-on:input="formatNumber($event)"
                                        class="relative w-full bg-white border border-gray-300 rounded-lg pl-3 pr-10 py-2 text-left focus:ring-1 focus:outline-0 focus:ring-primary focus:border-blue-500">
                                    <select wire:model.lazy="warranty_type"
                                        class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-2/5 appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                                        <option value="daily">Hari</option>
                                        <option value="weekly">Minggu</option>
                                        <option value="monthly">Bulan</option>
                                    </select>
                                </div>
                                @error('warranty')
                                    <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="w-full max-w-full px-3 shrink-0 md:flex-0">
                            <x-ui.button type="submit" title="Add Service" color="primary" wireLoading
                                formAction="addServiceItem" />
                        </div>

                    </div>
                </form>

            </div>
        </div>
    @elseif($formAction === 'edit')
        <div
            class=" border-black/12.5 dark:bg-slate-850 dark:shadow-dark-xl shadow-xl z-20 min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border items-center flex-1 custom-height">
            <div class="border-black/12.5 rounded-t-2xl border-b-0 border-solid p-6 pb-0">
                <div class="flex justify-between items-center">
                    <p class="mb-0 dark:text-white/80">
                        Edit Service Items
                    </p>
                    <button type="button" wire:loading.remove='$set("formAction", "")' type="button"
                        wire:click='$set("formAction", "")'>
                        <svg width="15px" height="15px" viewBox="-0.5 0 25 25" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 21.32L21 3.32001" stroke="#000000" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M3 3.32001L21 21.32" stroke="#000000" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </button>

                    <div wire:loading wire:target='$set("formAction", "")'>
                        <div class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                            role="status">
                            <span
                                class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-6 mt-4">
                <form wire:key='{{ time() }}' wire:submit.prevent="{{ $editAction }}" class="h-full">
                    <div class="flex flex-col justify-between h-full -mx-3">
                        <div>
                            <div class="mb-8 w-full max-w-full px-3 shrink-0 md:flex-0">
                                <label for="editService" class="text-sm">Service</label>
                                <input type="text" wire:model.lazy='editService' id="editService"
                                    class="relative w-full bg-white border border-gray-300 rounded-lg pl-3 pr-10 py-2 text-left focus:ring-1 focus:outline-0 focus:ring-primary focus:border-blue-500">
                                @error('editService')
                                    <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-8 w-full max-w-full px-3 shrink-0 md:flex-0">
                                <label for="editBiaya" class="text-sm">Biaya</label>
                                <input type="text" wire:model.lazy='editBiaya' id="editBiaya"
                                    x-data="{
                                        formatNumber: function(event) {
                                            const input = event.target;
                                            const value = input.value.replace(/\D/g, ''); // Remove non-numeric characters
                                            input.value = new Intl.NumberFormat('en-US').format(value);
                                        }
                                    }" x-on:input="formatNumber($event)"
                                    class="relative w-full bg-white border border-gray-300 rounded-lg pl-3 pr-10 py-2 text-left focus:ring-1 focus:outline-0 focus:ring-primary focus:border-blue-500">
                                @error('editBiaya')
                                    <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-8 w-full max-w-full px-3 shrink-0 md:flex-0">
                                @livewire('searchable-select', ['list' => $technician, 'selectedOption' => $editTechnical, 'name' => 'editTechnical', 'label' => 'Teknisi'])

                                @error('editTechnical')
                                    <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-8 w-full max-w-full px-3 shrink-0 md:flex-0">
                                @livewire('searchable-select', ['list' => $inventory, 'selectedOption' => $editProduct, 'name' => 'editProduct', 'label' => 'Sparepart'])
                                @error('editProduct')
                                    <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4 w-full max-w-full px-3 shrink-0 md:flex-0">
                                <label for="warranty" class="text-sm">Garansi</label>
                                <div class="flex flex-row gap-2">
                                    <input type="text" wire:model.lazy='warranty' id="warranty"
                                        x-data="{
                                            formatNumber: function(event) {
                                                const input = event.target;
                                                const value = input.value.replace(/\D/g, ''); // Remove non-numeric characters
                                                input.value = new Intl.NumberFormat('en-US').format(value);
                                            }
                                        }" x-on:input="formatNumber($event)"
                                        class="relative w-full bg-white border border-gray-300 rounded-lg pl-3 pr-10 py-2 text-left focus:ring-1 focus:outline-0 focus:ring-primary focus:border-blue-500">
                                    <select wire:model.lazy="warranty_type"
                                        class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-2/5 appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                                        <option value="daily">Hari</option>
                                        <option value="weekly">Minggu</option>
                                        <option value="monthly">Bulan</option>
                                    </select>
                                </div>
                                @error('warranty')
                                    <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="w-full max-w-full px-3 shrink-0 md:flex-0">
                            <x-ui.button type="submit" title="Edit Service" color="primary" wireLoading
                                formAction="{{ $editAction }}" />
                        </div>

                    </div>
                </form>

            </div>
        </div>
    @endif

    <script>
        // Listen for Livewire events
        window.addEventListener('refreshSelect', event => {
            // Trigger a re-render of the specified child component
            Livewire.emit('resetSelect');
        });
    </script>

</div>
