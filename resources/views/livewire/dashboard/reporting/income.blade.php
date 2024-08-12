<div>
    <div
        class="relative flex flex-col min-w-0 mb-4 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border h-full w-full p-4">
        <div class="flex gap-x-4 items-center w-full">
            <p class="mb-0">Filter : </p>
            <div class="w-full md:w-3/12 items-center">
                <select wire:model.debounce.500ms="selectedMonth"
                    class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                    @foreach ($listMonth as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-3/12 items-center">
                <select wire:model.debounce.500ms="selectedYear"
                    class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                    @foreach ($listYear as $item)
                        <option value="{{ $item }}">{{ $item }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-4/12 items-center">
                <select wire:model='selectTechnician'
                    class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                    <option value="">Please select technician</option>
                    @foreach ($technician as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="flex flex-col md:flex-row gap-4 justify-center w-full h-full">
        <div
            class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border h-full w-full">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <div class="flex flex-col md:flex-row justify-between px-3 pb-2">
                    <h6 class="dark:text-white">Income</h6>
                    <div class="text-slate-900 font-bold text-right">
                        <span>Rp {{ number_format($totalIncome) }}</span>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row justify-between px-3 pb-2">
                    <h6 class="dark:text-white">Expenditure</h6>
                    <div class="text-slate-900 font-bold text-right">
                        <span>Rp {{ number_format($totalExpenditure) }}</span>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row justify-between px-3 pb-2">
                    <h6 class="dark:text-white">Netto</h6>
                    <div class="text-slate-900 font-bold text-right">
                        <span>Rp {{ number_format($totalNetto) }}</span>
                    </div>
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
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($income as $index => $item)
                                <tr wire:key='income-{{ $index }}' wire:loading.remove
                                    wire:target='selectedMonth, selectedYear'>
                                    <td
                                        class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span
                                            class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                            {{ \Carbon\Carbon::parse($item['tanggal'])->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td
                                        class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span
                                            class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                            Rp {{ number_format($item['total']) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                            @for ($i = 0; $i <= 5; $i++)
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
                                </tr>
                            @endfor

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div
            class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border h-full w-full">

            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <div class="flex flex-col md:flex-row justify-between p-6 pb-0">
                    <h6 class="dark:text-white">Fee Technician Table</h6>
                    <div class="w-3/12 text-slate-900 font-bold text-right">
                        <span>Rp {{ number_format($totalFeeTeknisi) }}</span>
                    </div>
                </div>
            </div>


            <div class="flex-auto p-6">
                <div class="p-0 overflow-y-auto overflow-x-auto h-sidenav">
                    <table
                        class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                        <thead class="align-bottom">
                            <tr>
                                <th
                                    class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Tanggal
                                </th>
                                <th
                                    class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Service
                                </th>
                                <th
                                    class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataFeeTechnician as $index => $item)
                                <tr wire:key='fee-{{ $index }}' wire:loading.remove
                                    wire:target='selectedMonth, selectedYear, selectTechnician'>
                                    <td
                                        class="p-2 text-left align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span
                                            class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                            {{ \Carbon\Carbon::parse($item['created_at'])->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td
                                        class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span
                                            class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                            {{ $item['service'] }}
                                        </span>
                                    </td>
                                    <td
                                        class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span
                                            class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                            Rp {{ number_format($item['fee_teknisi']) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach

                            @for ($i = 0; $i <= 5; $i++)
                                <tr wire:loading.class="table-row" class="hidden" wire:loading.class.remove="hidden"
                                    wire:target='selectedMonth, selectedYear, selectTechnician'>
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
    </div>
</div>
