<div class="w-full px-6 py-4 mx-auto flex flex-col h-screen">
    {{-- Header Section --}}
    <div class="flex justify-between items-center">
        <div class="mb-0 border-b-0 border-solid">
            <h5 class="mb-1 font-serif">Financial Summary</h5>
            <p class="mb-0 text-sm leading-normal dark:text-white dark:opacity-60 font-serif">
                {{ \Carbon\Carbon::now()->format('l, d M Y') }}
            </p>
        </div>
        <div class="flex items-center md:ml-auto md:pr-4"></div>
    </div>

    <div class="flex flex-wrap -mx-3 mt-6 custom-height">
        <div class="flex-none w-full max-w-full px-3 h-full">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border h-full">
                {{-- Filter Section --}}
                <div class="block md:flex w-full justify-between items-center p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <div class="flex items-center gap-3 mb-2 md:mb-0">
                        <h6 class="mb-0 font-bold dark:text-white">{{ $listMonth[$selectedMonth] }} {{ $selectedYear }}</h6>
                    </div>
                    <div class="flex flex-col md:flex-row w-full md:w-8/12 items-end gap-3">
                        <div class="flex flex-col md:flex-row gap-2 w-full md:w-auto">
                            <div class="flex flex-col">
                                <label class="text-xs text-gray-600 mb-1">Month</label>
                                <select wire:model="selectedMonth"
                                    class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                                    @foreach ($listMonth as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col">
                                <label class="text-xs text-gray-600 mb-1">Year</label>
                                <select wire:model="selectedYear"
                                    class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                                    @foreach ($listYear as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if(auth()->user()->role === 'master_admin')
                            <button wire:click='exportToExcel' wire:loading.attr="disabled"
                                class="px-4 py-2 text-xs font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-green-500 leading-normal ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed whitespace-nowrap">
                                <span wire:loading.remove wire:target="exportToExcel">
                                    <i class="fas fa-file-excel mr-1"></i>
                                    Export
                                </span>
                                <span wire:loading wire:target="exportToExcel">
                                    <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                        role="status">
                                        <span class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                                    </div>
                                    Exporting...
                                </span>
                            </button>
                        @endif
                    </div>
                </div>

                {{-- Financial Summary Table --}}
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-0 overflow-x-auto">
                        <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                            <thead class="align-bottom">
                                <tr>
                                    <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Metric
                                    </th>
                                    <th class="px-6 py-3 pl-2 font-bold text-right uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Value
                                    </th>
                                    <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Total Income Row --}}
                                <tr>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <div class="flex px-2 py-1">
                                            <div class="flex items-center justify-center w-10 h-10 mr-4 text-center text-white bg-gradient-to-tl from-green-600 to-green-400 rounded-lg">
                                                <i class="ni ni-money-coins text-sm"></i>
                                            </div>
                                            <div class="flex flex-col justify-center">
                                                <h6 class="mb-0 text-sm leading-normal font-semibold">Total Income</h6>
                                                <p class="mb-0 text-xs leading-tight text-slate-400">Revenue from completed transactions</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-2 text-right align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <span class="text-xs font-semibold leading-tight text-slate-400">Rp {{ number_format($totalIncome) }}</span>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <span class="bg-gradient-to-tl from-green-600 to-green-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">
                                            Positive
                                        </span>
                                    </td>
                                </tr>

                                {{-- Total Expenditure Row --}}
                                <tr>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <div class="flex px-2 py-1">
                                            <div class="flex items-center justify-center w-10 h-10 mr-4 text-center text-white bg-gradient-to-tl from-red-600 to-red-400 rounded-lg">
                                                <i class="ni ni-cart text-sm"></i>
                                            </div>
                                            <div class="flex flex-col justify-center">
                                                <h6 class="mb-0 text-sm leading-normal font-semibold">Total Expenditure</h6>
                                                <p class="mb-0 text-xs leading-tight text-slate-400">Total business expenses</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-2 text-right align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <span class="text-xs font-semibold leading-tight text-slate-400">Rp {{ number_format($totalExpenditure) }}</span>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <span class="bg-gradient-to-tl from-red-600 to-red-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">
                                            Expense
                                        </span>
                                    </td>
                                </tr>

                                {{-- Net Profit Row --}}
                                <tr>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <div class="flex px-2 py-1">
                                            <div class="flex items-center justify-center w-10 h-10 mr-4 text-center text-white bg-gradient-to-tl {{ $netto >= 0 ? 'from-blue-600 to-blue-400' : 'from-orange-600 to-orange-400' }} rounded-lg">
                                                <i class="fas fa-chart-line text-sm"></i>
                                            </div>
                                            <div class="flex flex-col justify-center">
                                                <h6 class="mb-0 text-sm leading-normal font-semibold">Net Profit</h6>
                                                <p class="mb-0 text-xs leading-tight text-slate-400">Income minus expenditure</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-2 text-right align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <span class="text-xs font-semibold leading-tight text-slate-400">Rp {{ number_format(abs($netto)) }}</span>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <span class="bg-gradient-to-tl {{ $netto >= 0 ? 'from-green-600 to-green-400' : 'from-red-600 to-red-400' }} px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">
                                            {{ $netto >= 0 ? 'Profit' : 'Loss' }}
                                        </span>
                                    </td>
                                </tr>

                                {{-- Completed Transactions Row --}}
                                <tr>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <div class="flex px-2 py-1">
                                            <div class="flex items-center justify-center w-10 h-10 mr-4 text-center text-white bg-gradient-to-tl from-emerald-600 to-emerald-400 rounded-lg">
                                                <i class="fas fa-check text-sm"></i>
                                            </div>
                                            <div class="flex flex-col justify-center">
                                                <h6 class="mb-0 text-sm leading-normal font-semibold">Completed Transactions</h6>
                                                <p class="mb-0 text-xs leading-tight text-slate-400">Successfully completed orders</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-2 text-right align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <span class="text-xs font-semibold leading-tight text-slate-400">{{ number_format($totalDone) }} transactions</span>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <span class="bg-gradient-to-tl from-emerald-600 to-emerald-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">
                                            Complete
                                        </span>
                                    </td>
                                </tr>

                                {{-- Cancelled Transactions Row --}}
                                <tr>
                                    <td class="p-2 align-middle bg-transparent border-b-0 whitespace-nowrap shadow-transparent">
                                        <div class="flex px-2 py-1">
                                            <div class="flex items-center justify-center w-10 h-10 mr-4 text-center text-white bg-gradient-to-tl from-gray-600 to-gray-400 rounded-lg">
                                                <i class="fas fa-times text-sm"></i>
                                            </div>
                                            <div class="flex flex-col justify-center">
                                                <h6 class="mb-0 text-sm leading-normal font-semibold">Cancelled Transactions</h6>
                                                <p class="mb-0 text-xs leading-tight text-slate-400">Orders that were cancelled</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-2 text-right align-middle bg-transparent border-b-0 whitespace-nowrap shadow-transparent">
                                        <span class="text-xs font-semibold leading-tight text-slate-400">{{ number_format($totalCancel) }} transactions</span>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b-0 whitespace-nowrap shadow-transparent">
                                        <span class="bg-gradient-to-tl from-gray-600 to-gray-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">
                                            Cancelled
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

