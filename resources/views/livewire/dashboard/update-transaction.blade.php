<div class="grid grid-cols-5 gap-x-4 justify-center mb-4">
    <div class="col-span-3">
        <div
            class=" border-black/12.5 dark:bg-slate-850 dark:shadow-dark-xl shadow-xl z-20 min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border items-center flex-1">
            <div class="border-black/12.5 rounded-t-2xl border-b-0 border-solid p-6 pb-0">
                <div class="flex justify-between items-center">
                    <p class="mb-0 dark:text-white/80">Update Transaction</p>
                    <p>ORDER ID : {{ $transaction['order_transaction'] }}</p>
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
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr wire:key='{{ $transaction['order_transaction'] }}' wire:loading.remove
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
                                            <tr wire:key='{{ $item->id }}' wire:loading.remove
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
                        <div class="flex flex-row gap-x-2 items-center">
                            <div class="w-full max-w-full px-3">
                                <x-ui.button type="button" title="Close" color="danger" wireLoading
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
    <div class="col-span-2">
        <div
            class=" border-black/12.5 dark:bg-slate-850 dark:shadow-dark-xl shadow-xl z-20 min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border items-center flex-1 custom-height">
            <div class="border-black/12.5 rounded-t-2xl border-b-0 border-solid p-6 pb-0">
                <div class="flex justify-between items-center">
                    <p class="mb-0 dark:text-white/80">
                        {{ $formAction === 'add' ? 'Add Service Items' : 'Edit Service Items' }}</p>
                </div>
            </div>
            <div class="p-6 mt-4">
                <form wire:submit.prevent="{{ $formAction === 'add' ? 'addServiceItem' : $editAction }}"
                    class="h-full">
                    <div class="flex flex-col justify-between h-full -mx-3">
                        <div>
                            <div class="mb-8 w-full max-w-full px-3 shrink-0 md:flex-0">
                                <div wire:ignore>
                                    <x-ui.input label="Service" wire:model="editService" id="editService" />
                                </div>
                                @error('editService')
                                    <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-8 w-full max-w-full px-3 shrink-0 md:flex-0">
                                <div wire:ignore>
                                    <x-ui.input label="Biaya" wire:model="editBiaya" id="editBiaya"
                                        x-data="{
                                            formatNumber: function(event) {
                                                const input = event.target;
                                                const value = input.value.replace(/\D/g, ''); // Remove non-numeric characters
                                                input.value = new Intl.NumberFormat('en-US').format(value);
                                            }
                                        }" x-on:input="formatNumber($event)" />
                                </div>
                                @error('editBiaya')
                                    <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-8 w-full max-w-full px-3 shrink-0 md:flex-0">
                                <div wire:ignore>
                                    <x-ui.select label="Teknisi" wire:model="editTechnical" id="editTechnical" search
                                        size="lg">
                                        <option value=""></option>
                                        @foreach ($technician as $value)
                                            <option value="{{ $value->id }}">{{ $value->name }}
                                            </option>
                                        @endforeach
                                    </x-ui.select>
                                </div>
                                @error('editTechnical')
                                    <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-8 w-full max-w-full px-3 shrink-0 md:flex-0">
                                <div wire:ignore>
                                    <x-ui.select label="Sparepart" wire:model="editProduct" id="editProduct" search
                                        size="lg">
                                        <option value=""></option>
                                        @foreach ($inventory as $index => $value)
                                            <option value="{{ $index }}">{{ $value }}
                                            </option>
                                        @endforeach
                                    </x-ui.select>
                                </div>
                                @error('editProduct')
                                    <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="w-full max-w-full px-3 shrink-0 md:flex-0">
                            <x-ui.button type="submit" title="{{ $formAction === 'add' ? 'Add' : 'Edit' }} Service"
                                color="primary" wireLoading
                                formAction="{{ $formAction === 'add' ? 'addServiceItem' : $editAction }}" />
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>

</div>
