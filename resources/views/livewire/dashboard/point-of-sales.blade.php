<div class="flex flex-row gap-x-4 justify-center">
    <div
        class="w-2/3 border-black/12.5 dark:bg-slate-850 dark:shadow-dark-xl shadow-xl rounded-2xl border-0 border-solid bg-white bg-clip-border items-center mb-4">
        <div class="border-black/12.5 rounded-t-2xl border-b-0 border-solid p-6 pb-0">
            <div class="flex justify-between items-center">
                <p class="mb-0 dark:text-white/80">Transaction</p>
                <p>ORDER ID : {{ $order_transaction }}</p>
            </div>
        </div>
        <div class="p-6">
            <form wire:submit.prevent="submit" class="h-full">
                <div class="flex flex-col justify-between -mx-3">
                    <div class="mb-8">
                        <div class="w-full grid grid-cols-4 gap-x-3 items-end">
                            <div class="col-span-3">
                                <div class="w-full max-w-full px-3 shrink-0 md:flex-0">
                                    @livewire('searchable-select', ['list' => $customers, 'selectedOption' => $customer_id, 'name' => 'customer_id', 'label' => 'Customer'])
                                    @error('customer_id')
                                        <div class="text-red-500 text-sm">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-span-1">
                                <button type="button" wire:click='create'
                                    class="w-full p-3 text-xs font-bold leading-normal text-center text-white capitalize transition-all ease-in rounded-lg shadow-md bg-slate-700 bg-150 hover:shadow-xs hover:-translate-y-px ">
                                    <div wire:loading wire:target='create'>
                                        <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                            role="status">
                                            <span
                                                class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                                        </div>
                                    </div>
                                    <span wire:loading.remove wire:target='create'>New Customer</span>
                                </button>
                            </div>
                        </div>
                        <div class="px-0 pt-0 mt-4 pb-4 overflow-auto min-h-80">
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
                                        @foreach ($serviceItems as $index => $item)
                                            <tr wire:key='{{ $index }}' wire:loading.remove
                                                wire:target='gotoPage, previousPage, nextPage, searchTerm'>
                                                <td
                                                    class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span
                                                        class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">{{ $item['service'] }}</span>
                                                </td>
                                                <td
                                                    class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span
                                                        class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">{{ number_format($item['biaya']) }}</span>
                                                </td>
                                                <td
                                                    class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span
                                                        class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">{{ $item['technical_name'] !== '' ? $item['technical_name'] : '-' }}</span>
                                                </td>
                                                <td
                                                    class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span
                                                        class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">{{ $item['product_name'] !== '' ? $item['product_name'] : '-' }}</span>
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
                    </div>

                    <div class="px-3">
                        <x-ui.button type="submit" title="Submit" color="primary" wireLoading formAction="submit" />
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div
        class="w-1/3 border-black/12.5 dark:bg-slate-850 dark:shadow-dark-xl shadow-xl rounded-2xl border-0 border-solid bg-white bg-clip-border items-center mb-4">
        <div class="border-black/12.5 rounded-t-2xl border-b-0 border-solid p-6 pb-0">
            <div class="flex justify-between items-center">
                <p class="mb-0 dark:text-white/80">Add Service Items</p>
            </div>
        </div>
        <div class="p-6 mt-4">
            <form wire:submit.prevent="addServiceItems">
                <div class="flex flex-col justify-between h-full -mx-3">
                    <div>
                        <div class="mb-4 w-full max-w-full px-3 shrink-0 md:flex-0">
                            <label for="service" class="text-sm">Service</label>
                            <input type="text" wire:model.lazy='service' id="service"
                                class="relative w-full bg-white border border-gray-300 rounded-lg pl-3 pr-10 py-2 text-left focus:ring-1 focus:outline-0 focus:ring-primary focus:border-blue-500">
                            @error('service')
                                <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4 w-full max-w-full px-3 shrink-0 md:flex-0">
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
                        <div class="mb-4 w-full max-w-full px-3 shrink-0 md:flex-0">
                            @livewire('searchable-select', ['list' => $technician, 'selectedOption' => $technical_id, 'name' => 'technical_id', 'label' => 'Teknisi'])
                            @error('technical_id')
                                <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4 w-full max-w-full px-3 shrink-0 md:flex-0">
                            @livewire('searchable-select', ['list' => $inventory, 'selectedOption' => $product_id, 'name' => 'product_id', 'label' => 'Sparepart'])
                            @error('product_id')
                                <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4 w-full max-w-full px-3 shrink-0 md:flex-0">
                            <label for="warranty" class="text-sm">Garansi</label>
                            <div class="flex flex-row gap-2">
                                <input type="text" wire:model.lazy='warranty' id="warranty" x-data="{
                                    formatNumber: function(event) {
                                        const input = event.target;
                                        const value = input.value.replace(/\D/g, ''); // Remove non-numeric characters
                                        input.value = new Intl.NumberFormat('en-US').format(value);
                                    }
                                }"
                                    x-on:input="formatNumber($event)"
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
                    <div class="w-full max-w-full px-3 shrink-0 md:flex-0 mt-4">
                        <x-ui.button type="submit" title="Add Service" color="primary" wireLoading
                            formAction="addServiceItems" />
                    </div>

                </div>
            </form>

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
                                Add New Customer
                            </h3>
                            <div class="mt-2">
                                <div class="mt-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                    <div class="mt-1 relative rounded-md">
                                        <input type="text" wire:model.lazy="name" id="name"
                                            class="input input-md h-11 focus:ring-indigo-600 focus-within:ring-indigo-600 focus-within:border-indigo-600 focus:border-indigo-600">
                                    </div>
                                    @error('name')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mt-4">
                                    <label for="no_telp" class="block text-sm font-medium text-gray-700">No
                                        Telefon</label>
                                    <div class="mt-1 relative rounded-md">
                                        <input type="text" wire:model.lazy="no_telp" id="no_telp"
                                            class="input input-md h-11 focus:ring-indigo-600 focus-within:ring-indigo-600 focus-within:border-indigo-600 focus:border-indigo-600">
                                    </div>
                                    @error('no_telp')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mt-4">
                                    <label for="alamat" class="block text-sm font-medium text-gray-700">Address
                                        (optional)</label>
                                    <div class="mt-1 relative rounded-md">
                                        <input type="text" wire:model.lazy="alamat" id="alamat"
                                            class="input input-md h-11 focus:ring-indigo-600 focus-within:ring-indigo-600 focus-within:border-indigo-600 focus:border-indigo-600">
                                    </div>
                                    @error('alamat')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-x-2 mt-5 sm:mt-6">
                        <button wire:click="storeNewCustomer" wire:loading.attr="disabled"
                            wire:target="storeNewCustomer"
                            class="inline-flex gap-x-2 justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-500 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                            <div wire:loading wire:target='storeNewCustomer'>
                                <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                    role="status">
                                    <span
                                        class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                                </div>
                            </div>
                            Save
                        </button>
                        <button wire:click="closeModal" wire:loading.attr="disabled" wire:target="closeModal"
                            class="inline-flex gap-x-2 justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-500 text-base font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:text-sm">
                            <div wire:loading wire:target='closeModal'>
                                <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                    role="status">
                                    <span
                                        class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                                </div>
                            </div>
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        // Listen for Livewire events
        window.addEventListener('refreshSelect', event => {
            // Trigger a re-render of the specified child component
            Livewire.emit('resetSelect', event.detail);
        });
    </script>
</div>

@push('style')
    <style>
        .custom-height-pos {
            min-height: calc(100vh - 155px);
        }

        .table-height-pos {
            min-height: calc(100vh - 400px);
        }
    </style>
@endpush

@push('script')
    <script>
        function updateSelectOptions(newOptions) {
            const selectElement = document.getElementById('customer_id');

            // Clear existing options
            selectElement.innerHTML = '';

            // Add new options
            newOptions.forEach(option => {
                const optionElement = document.createElement('option');
                optionElement.value = option.value;
                optionElement.textContent = option.text;
                selectElement.appendChild(optionElement);
            });
        }

        window.livewire.on('updateCustomer', (newCustomer) => {
            let newOptions = [{
                value: '',
                text: ''
            }]

            newCustomer.forEach(element => {
                newOptions.push({
                    value: element.id,
                    text: element.no_telp + "(" + element.name + ")"
                })
            });


            updateSelectOptions(newOptions);

        });
    </script>
@endpush
