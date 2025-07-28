<div
    class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border custom-height w-full">
    <div class="flex flex-row md:flex-row justify-between p-6 pb-0 ">
        <h6 class="dark:text-white">Transactions Cancel</h6>
        <div class="w-full md:w-3/12 items-center">
            <input type="text" wire:model.debounce.500ms="searchTerm"
                class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"
                placeholder="Masukkan order id" />
            <div class="flex gap-x-2 mt-2">
                <div class="w-full">
                    <div class="flex items-center">
                        <select wire:model="month"
                            class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                            <option value="" selected>Pilih Bulan</option>
                            <option value="01">Januari</option>
                            <option value="02">Februari</option>
                            <option value="03">Maret</option>
                            <option value="04">April</option>
                            <option value="05">Mei</option>
                            <option value="06">Juni</option>
                            <option value="07">Juli</option>
                            <option value="08">Agustus</option>
                            <option value="09">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                    </div>
                    @if ($errors->has('month'))
                        <span class="text-red-500 text-xs">{{ $errors->first('month') }}</span>
                    @endif
                </div>

                <div class="w-full">
                    <div class="flex items-center">
                        <select wire:model="year"
                            class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                            <option value="" selected>Pilih Tahun</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                            <option value="2027">2027</option>
                            <option value="2028">2028</option>
                        </select>
                    </div>
                    @if ($errors->has('year'))
                        <span class="text-red-500 text-xs">{{ $errors->first('year') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="flex-auto p-6 h-full">
        <div class="p-0 overflow-y-auto table-height">
            <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                <thead class="align-bottom">
                    <tr>
                        <th
                            class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                            No
                        </th>
                        <th
                            class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                            Tanggal
                        </th>
                        <th
                            class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                            Customer
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
                            Status
                        </th>
                        <th
                            class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">

                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $index => $item)
                        <tr wire:key='{{ $index . time() }}' wire:loading.remove
                            wire:target='gotoPage, previousPage, nextPage, searchTerm, selectedDate, selectedPaymentMethod, delete'>
                            <td
                                class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                <span
                                    class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                    {{ $index + 1 }}
                                </span>
                            </td>
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
                                    {{ $item->customer_name }}
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
                                @if ($item->service_name !== null)
                                    <div class="relative group">
                                        <span
                                            class="text-xs cursor-pointer font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                            {{ $item->service }} ...
                                        </span>

                                        <div
                                            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 hidden group-hover:block bg-gray-800 text-white text-sm rounded px-3 py-2">
                                            {{ $item->service_name }}
                                        </div>
                                    </div>
                                @else
                                    <span
                                        class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                        {{ $item->service }}
                                    </span>
                                @endif

                            </td>
                            <td
                                class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                <span
                                    class="bg-danger-500 rounded text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-neutral-50 capitalize px-2 py-1">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td
                                class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                <div class="flex flex-row gap-x-3">
                                    <button wire:click='detail("{{ $item->id }}")'
                                        class="inline-flex gap-x-2 items-center px-3 py-2 text-xs mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-primary leading-normal  ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md">
                                        <div wire:loading wire:target='detail("{{ $item->id }}")'>
                                            <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                                role="status">
                                                <span
                                                    class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                                            </div>
                                        </div>
                                        <span>Detail</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    @for ($i = 0; $i <= 10; $i++)
                        <tr wire:loading.class="table-row" class="hidden" wire:loading.class.remove="hidden"
                            wire:target='gotoPage, previousPage, nextPage, searchTerm, selectedDate, selectedPaymentMethod, delete'>
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
                    class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6">

                    <div class="border-b-2 flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Detail Transaction
                        </h3>
                        <button wire:loading.remove='closeModal' type="button" wire:click='closeModal'>
                            <svg width="20px" height="20px" viewBox="-0.5 0 25 25" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 21.32L21 3.32001" stroke="#000000" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M3 3.32001L21 21.32" stroke="#000000" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>

                        <div wire:loading wire:target='closeModal'>
                            <div class="inline-block h-5 w-5 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                role="status">
                                <span
                                    class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                            </div>
                        </div>
                    </div>


                    <div class="flex justify-between mt-4">
                        <div class="flex gap-x-3">
                            <p class="mb-0 text-neutral-900 leading-6 text-sm">Customer</p>
                            <p class="mb-0 text-neutral-900 leading-6 text-sm">:</p>
                            <p class="mb-0 text-neutral-900 leading-6 text-sm">{{ $detailItem['customer_name'] }}</p>
                        </div>

                        <div>
                            <p class="mb-0 text-neutral-900 leading-6 text-sm">#{{ $detailItem['order_transaction'] }}
                            </p>
                            <p class="mb-0 text-neutral-900 leading-6 text-sm">
                                {{ \Carbon\Carbon::parse($detailItem['created_at'])->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <div class="flex gap-x-2 mt-2">
                        <div>
                            <p class="mb-0 text-neutral-900 leading-6 text-sm">Total</p>
                            <p class="mb-0 text-neutral-900 leading-6 text-sm">Status</p>
                        </div>
                        <div>
                            <p class="mb-0 text-neutral-900 leading-6 text-sm">:</p>
                            <p class="mb-0 text-neutral-900 leading-6 text-sm">:</p>
                        </div>
                        <div>
                            <p class="mb-0 text-neutral-900 leading-6 text-sm">
                                Rp{{ number_format($detailItem['total']) }}</p>
                            <p class="mb-0 text-neutral-900 leading-6 text-sm capitalize">{{ $detailItem['status'] }}
                            </p>
                        </div>
                    </div>

                    {{-- Phone Details Section --}}
                    @if ($detailItem['phone_type'] || (isset($detailItem['items']) && count($detailItem['items']) > 0 && collect($detailItem['items'])->contains(function($item) {
                        return !empty($item['phone_type']);
                    })))
                        <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Phone Details</h4>
                            @if ($detailItem['phone_type'])
                                <div class="mb-2">
                                    <div class="flex gap-x-2 text-sm">
                                        <span class="text-gray-600">Main Transaction:</span>
                                        <span class="text-gray-900 font-medium">
                                            {{ $detailItem['phone_brand'] ?? 'iPhone' }} {{ $detailItem['phone_type'] }}
                                            @if ($detailItem['phone_internal'])
                                                - {{ $detailItem['phone_internal'] }}@if (in_array($detailItem['phone_internal'], ['1T', '2T']))TB @else GB @endif
                                            @endif
                                            @if ($detailItem['phone_color'])
                                                - {{ $detailItem['phone_color'] }}
                                            @endif
                                        </span>
                                    </div>
                                    @if ($detailItem['phone_imei'])
                                        <div class="flex gap-x-2 text-sm mt-1">
                                            <span class="text-gray-600">IMEI:</span>
                                            <span class="text-gray-900">{{ $detailItem['phone_imei'] }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            
                            @foreach ($detailItem['items'] as $index => $item)
                                @if (!empty($item['phone_type']))
                                    <div class="mb-2 @if ($detailItem['phone_type']) border-t pt-2 @endif">
                                        <div class="flex gap-x-2 text-sm">
                                            <span class="text-gray-600">{{ $item['service'] }}:</span>
                                            <span class="text-gray-900 font-medium">
                                                {{ $item['phone_brand'] ?? 'iPhone' }} {{ $item['phone_type'] }}
                                                @if ($item['phone_internal'])
                                                    - {{ $item['phone_internal'] }}@if (in_array($item['phone_internal'], ['1T', '2T']))TB @else GB @endif
                                                @endif
                                                @if ($item['phone_color'])
                                                    - {{ $item['phone_color'] }}
                                                @endif
                                            </span>
                                        </div>
                                        @if ($item['phone_imei'])
                                            <div class="flex gap-x-2 text-sm mt-1">
                                                <span class="text-gray-600">IMEI:</span>
                                                <span class="text-gray-900">{{ $item['phone_imei'] }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    <div class="mt-3">
                        <table
                            class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                            <thead class="align-bottom">
                                <tr>
                                    <th
                                        class="text-left py-3 px-2 font-bold uppercase bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Service</th>
                                    <th
                                        class="text-left py-3 px-2 font-bold uppercase bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Biaya</th>
                                    <th
                                        class="text-left py-3 px-2 font-bold uppercase bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Teknisi</th>
                                    <th
                                        class="text-left py-3 px-2 font-bold uppercase bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Sparepart</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>

                                    <td
                                        class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span
                                            class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                            {{ $detailItem['service'] }}
                                        </span>
                                    </td>
                                    <td
                                        class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span
                                            class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                            {{ number_format($detailItem['biaya']) }}
                                        </span>
                                    </td>
                                    <td
                                        class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span
                                            class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                            {{ \App\Models\Technician::getTechnicalName($detailItem['technical_id']) ?? '-' }}
                                        </span>
                                    </td>
                                    <td
                                        class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span
                                            class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                            {{ \App\Models\Product::getProductName($detailItem['product_id']) ?? '-' }}
                                        </span>
                                    </td>
                                </tr>
                                @foreach ($detailItem['items'] as $item)
                                    <tr>
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
                                                {{ \App\Models\Technician::getTechnicalName($item['technical_id']) ?? '-' }}
                                            </span>
                                        </td>
                                        <td
                                            class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                {{ \App\Models\Product::getProductName($item['product_id']) ?? '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('style')
    <style>
        []::after {
            content: " ";
            box-shadow: 0 0 50px 9px rgba(254, 254, 254);
            position: absolute;
            top: 0;
            left: -100%;
            height: 100%;
            animation: load 1s infinite;
        }

        @keyframes load {
            0% {
                left: -100%
            }

            100% {
                left: 150%
            }
        }

        .custom-height {
            min-height: calc(100vh - 155px);
        }

        .table-height {
            min-height: calc(100vh - 320px);
        }
    </style>
@endpush
