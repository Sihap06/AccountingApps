<div class="w-full">
    <div class="grid grid-cols-1 lg:grid-cols-1 gap-x-6 gap-y-6">
        <!-- Left Panel - Transaction Items -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-slate-850 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                <!-- Customer Selection -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Customer Information</h3>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">ID Order:</span>
                            <span
                                class="font-mono bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded text-sm font-semibold text-gray-500 dark:text-gray-400 italic">Auto-assigned on save</span>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <div class="flex-1">
                            @livewire('searchable-select', ['list' => $customers, 'selectedOption' => $customer_id, 'name' => 'customer_id', 'label' => 'Select Customer'])
                            @error('customer_id')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="flex items-end">
                            <button type="button" wire:click='create'
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 flex items-center h-[38px]">
                                <div wire:loading wire:target='create'>
                                    <div
                                        class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent">
                                    </div>
                                </div>
                                <span wire:loading.remove wire:target='create'>
                                    <i class="fas fa-plus mr-1"></i>New
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Transaction Items Table -->
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Service List</h3>

                    @if (count($serviceItems) > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-800">
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                            Service</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                            Cost</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                            Technician</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                            Parts/Sparepart</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                            Price Basis</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                            Warranty</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                            Phone Info</th>
                                        <th
                                            class="px-4 py-3 text-center text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($serviceItems as $index => $item)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-300">
                                                {{ $item['service'] }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-300">
                                                <div>
                                                    Rp {{ number_format($item['biaya'] - ($item['potongan'] ?? 0)) }}
                                                </div>
                                                @if (isset($item['potongan']) && $item['potongan'] > 0)
                                                    <div class="text-xs text-gray-500">
                                                        <span class="line-through">Rp
                                                            {{ number_format($item['biaya']) }}</span>
                                                        <span class="text-red-600">
                                                            -{{ number_format($item['potongan']) }}</span>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-300">
                                                {{ $item['technical_name'] ?: '-' }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-300">
                                                {{ $item['product_name'] ?: '-' }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-300">
                                                Rp {{ number_format($item['modal'] ?? 0) }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-300">
                                                @if ($item['warranty'])
                                                    {{ $item['warranty'] }}
                                                    {{ $item['warranty_type'] == 'daily' ? 'Days' : ($item['warranty_type'] == 'weekly' ? 'Weeks' : 'Months') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-300">
                                                @if (
                                                    $item['phone_brand'] ||
                                                        $item['phone_type'] ||
                                                        $item['phone_internal'] ||
                                                        $item['phone_color'] ||
                                                        $item['phone_imei']
                                                )
                                                    <div class="space-y-1">
                                                        @if ($item['phone_type'])
                                                            <div class="font-medium">{{ $item['phone_type'] }}</div>
                                                        @endif
                                                        @if ($item['phone_internal'] || $item['phone_color'])
                                                            <div class="text-xs text-gray-600 dark:text-gray-400">
                                                                @if ($item['phone_internal'])
                                                                    {{ $item['phone_internal'] }}@if ($item['phone_internal'] == '1T' || $item['phone_internal'] == '2T')
                                                                        TB
                                                                    @else
                                                                        GB
                                                                    @endif
                                                                @endif
                                                                @if ($item['phone_internal'] && $item['phone_color'])
                                                                    •
                                                                @endif
                                                                @if ($item['phone_color'])
                                                                    {{ $item['phone_color'] }}
                                                                @endif
                                                            </div>
                                                        @endif
                                                        @if ($item['phone_imei'])
                                                            <div class="text-xs text-gray-500 dark:text-gray-500">
                                                                IMEI: {{ $item['phone_imei'] }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <button type="button"
                                                    wire:click="removeServiceItem({{ $index }})"
                                                    class="text-red-600 hover:text-red-800 transition-colors">
                                                    <i class="fas fa-trash-alt" wire:loading.remove
                                                        wire:target='removeServiceItem({{ $index }})'></i>
                                                    <div wire:loading
                                                        wire:target='removeServiceItem({{ $index }})'>
                                                        <div
                                                            class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-solid border-current border-r-transparent">
                                                        </div>
                                                    </div>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Total Section -->
                        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-semibold text-gray-800 dark:text-white">Total:</span>
                                <span class="text-2xl font-bold text-primary dark:text-primary-400">
                                    Rp
                                    {{ number_format(collect($serviceItems)->sum(function ($item) {return $item['biaya'] - ($item['potongan'] ?? 0);})) }}
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-shopping-cart text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">No services added yet</p>
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <div class="mt-6">
                        <form wire:submit.prevent="submit">
                            <button type="submit"
                                class="w-full bg-primary hover:bg-primary-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                @if (count($serviceItems) == 0) disabled @endif>
                                <div wire:loading wire:target='submit'
                                    class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-solid border-current border-r-transparent mr-2">
                                </div>
                                <span wire:loading.remove wire:target='submit'>Process Transaction</span>
                                <span wire:loading wire:target='submit'>Processing...</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Add Service Form -->
        <div class="lg:col-span-1">
            <div
                class="bg-white dark:bg-slate-850 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 sticky top-4">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Add Service</h3>
                </div>

                <form wire:submit.prevent="addServiceItems" class="p-6">
                    <div class="space-y-4">
                        <!-- Service & Cost Group -->
                        <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg space-y-4">
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Service Details</h4>

                            <div>
                                <x-ui.input-default wire:model.lazy="service" label="Service"
                                    placeholder="Enter service name" required="true" :error="$errors->first('service')" />
                            </div>

                            <div>
                                <div class="relative">
                                    <x-ui.input-default wire:model="biaya" id="biaya" label="Cost (Rp)"
                                        placeholder="0" required="true" :error="$errors->first('biaya')" :disabled="$product_id ? true : false"
                                        x-data="{
                                            formatNumber: function(event) {
                                                const input = event.target;
                                                const value = input.value.replace(/\D/g, '');
                                                input.value = new Intl.NumberFormat('en-US').format(value);
                                            },
                                            init() {
                                                this.$watch('$wire.biaya', (value) => {
                                                    if (value && value.toString().includes('.')) {
                                                        this.$el.value = value;
                                                    }
                                                });
                                            }
                                        }" x-on:input="formatNumber($event)" />
                                </div>
                            </div>


                            <div>
                                <div class="relative">
                                    <x-ui.input-default wire:model="potongan" id="potongan" label="Discount (Rp)"
                                        placeholder="0" :error="$errors->first('potongan')" x-data="{
                                            formatNumber: function(event) {
                                                const input = event.target;
                                                const value = input.value.replace(/\D/g, '');
                                                input.value = new Intl.NumberFormat('en-US').format(value);
                                            },
                                            init() {
                                                this.$watch('$wire.potongan', (value) => {
                                                    if (value && value.toString().includes('.')) {
                                                        this.$el.value = value;
                                                    }
                                                });
                                            }
                                        }"
                                        x-on:input="formatNumber($event)" />
                                </div>
                            </div>
                        </div>

                        <!-- Technician & Spare Part Group -->
                        <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg space-y-4">
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Assignment</h4>

                            <div>
                                @livewire('searchable-select', ['list' => $technician, 'selectedOption' => $technical_id, 'name' => 'technical_id', 'label' => 'Technician (Optional)'])
                                @error('technical_id')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                @livewire('searchable-select', ['list' => $inventory, 'selectedOption' => $product_id, 'name' => 'product_id', 'label' => 'Parts/Sparepart (Optional)'])
                                @error('product_id')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label
                                    class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white dark:text-opacity-80">
                                    Warranty (Optional)
                                </label>
                                <div class="flex space-x-2">
                                    <input type="text" wire:model.lazy='warranty' id="warranty" placeholder="0"
                                        class="flex-1 focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"
                                        x-data="{
                                            formatNumber: function(event) {
                                                const input = event.target;
                                                const value = input.value.replace(/\D/g, '');
                                                input.value = new Intl.NumberFormat('en-US').format(value);
                                            }
                                        }" x-on:input="formatNumber($event)">
                                    <select wire:model.lazy="warranty_type"
                                        class="w-32 focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                                        <option value="daily">Days</option>
                                        <option value="weekly">Weeks</option>
                                        <option value="monthly">Months</option>
                                    </select>
                                </div>
                                @error('warranty')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Phone Details Group -->
                        <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg space-y-4">
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Phone Details
                                (Optional)</h4>

                            <div class="grid grid-cols-2 gap-x-3">
                                <div>
                                    <x-ui.input-default wire:model.lazy="phone_brand" label="Brand"
                                        placeholder="e.g. iPhone, Samsung, Xiaomi" />
                                </div>

                                <div>
                                    <x-ui.input-default wire:model.lazy="phone_internal" label="Storage"
                                        placeholder="e.g. 128GB" />
                                </div>
                            </div>

                            <div>
                                <x-ui.input-default wire:model.lazy="phone_type" label="Model"
                                    placeholder="e.g. iPhone 14 Pro Max" />
                            </div>

                            <div>
                                <x-ui.input-default wire:model.lazy="phone_color" label="Color"
                                    placeholder="e.g. Space Gray" />
                            </div>

                            <div>
                                <x-ui.input-default wire:model.lazy="phone_imei" label="IMEI"
                                    placeholder="Enter IMEI number" />
                            </div>
                        </div>

                        <!-- Add Button -->
                        <button type="submit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 rounded-lg transition-colors duration-200">
                            <div wire:loading wire:target='addServiceItems'
                                class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-solid border-current border-r-transparent mr-2">
                            </div>
                            <span wire:loading.remove wire:target='addServiceItems'>
                                <i class="fas fa-plus mr-2"></i>Add Service
                            </span>
                            <span wire:loading wire:target='addServiceItems'>Adding...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- New Customer Modal -->
    @if ($isOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6 transform transition-all">
                    <div class="absolute top-4 right-4">
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-900 mb-6">Add New Customer</h3>

                    <div class="space-y-4">
                        <div>
                            <x-ui.input-default wire:model.lazy="name" label="Name"
                                placeholder="Enter customer name" required="true" :error="$errors->first('name')" />
                        </div>

                        <div>
                            <x-ui.input-default wire:model.lazy="no_telp" label="Phone Number"
                                placeholder="Enter phone number" required="true" :error="$errors->first('no_telp')" />
                        </div>

                        <div>
                            <label for="alamat"
                                class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white dark:text-opacity-80">
                                Address <span class="text-gray-400">(Optional)</span>
                            </label>
                            <textarea wire:model.lazy="alamat" id="alamat" rows="3"
                                class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"
                                placeholder="Enter customer address"></textarea>
                            @error('alamat')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="flex space-x-3 mt-6">
                        <button wire:click="storeNewCustomer" wire:loading.attr="disabled"
                            wire:target="storeNewCustomer"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-lg transition-colors duration-200 disabled:opacity-50">
                            <div wire:loading wire:target='storeNewCustomer'
                                class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-solid border-current border-r-transparent mr-2">
                            </div>
                            <span wire:loading.remove wire:target='storeNewCustomer'>Save Customer</span>
                            <span wire:loading wire:target='storeNewCustomer'>Saving...</span>
                        </button>
                        <button wire:click="closeModal" wire:loading.attr="disabled" wire:target="closeModal"
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-2 rounded-lg transition-colors duration-200">
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
        /* Custom scrollbar for tables */
        .overflow-x-auto::-webkit-scrollbar {
            height: 6px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #555;
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
