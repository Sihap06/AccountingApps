<div class="space-y-6">
    <!-- Enhanced Filter Section -->
    <div class="bg-white dark:bg-slate-850 rounded-xl shadow-lg p-6">
        <div class="flex items-center mb-4">
            <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                </path>
            </svg>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Filters</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Month</label>
                <select wire:model.debounce.500ms="selectedMonth"
                    class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                    @foreach ($listMonth as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Year</label>
                <select wire:model.debounce.500ms="selectedYear"
                    class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                    @foreach ($listYear as $item)
                        <option value="{{ $item }}">{{ $item }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Technician</label>
                <select wire:model='selectTechnician'
                    class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                    <option value="">All Technicians</option>
                    @foreach ($technician as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Income Card -->
        <div
            class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 rounded-xl p-6 shadow-lg transform transition-all duration-200 hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 dark:text-green-300 text-sm font-medium">Total Income</p>
                    <p class="text-2xl font-bold text-green-800 dark:text-white mt-2">
                        Rp {{ number_format($totalIncome, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>
                        {{ $listMonth[$selectedMonth] }} {{ $selectedYear }}
                    </p>
                </div>
                <div class="bg-green-200 dark:bg-green-700 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Expenditure Card -->
        <div
            class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900 dark:to-red-800 rounded-xl p-6 shadow-lg transform transition-all duration-200 hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-600 dark:text-red-300 text-sm font-medium">Total Expenditure</p>
                    <p class="text-2xl font-bold text-red-800 dark:text-white mt-2">
                        Rp {{ number_format($totalExpenditure, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-red-600 dark:text-red-400 mt-1">
                        <i class="fas fa-arrow-down mr-1"></i>
                        {{ $listMonth[$selectedMonth] }} {{ $selectedYear }}
                    </p>
                </div>
                <div class="bg-red-200 dark:bg-red-700 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Net Profit Card -->
        <div
            class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 rounded-xl p-6 shadow-lg transform transition-all duration-200 hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 dark:text-blue-300 text-sm font-medium">Net Profit</p>
                    <p
                        class="text-2xl font-bold {{ $totalNetto >= 0 ? 'text-blue-800' : 'text-red-600' }} dark:text-white mt-2">
                        Rp {{ number_format($totalNetto, 0, ',', '.') }}
                    </p>
                    <p
                        class="text-xs {{ $totalNetto >= 0 ? 'text-blue-600' : 'text-red-600' }} dark:{{ $totalNetto >= 0 ? 'text-blue-400' : 'text-red-400' }} mt-1">
                        <i class="fas {{ $totalNetto >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                        {{ $totalNetto >= 0 ? 'Profit' : 'Loss' }}
                    </p>
                </div>
                <div class="bg-blue-200 dark:bg-blue-700 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="bg-white dark:bg-slate-850 rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Quick Statistics</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">Daily Average</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                    Rp {{ number_format($income->count() > 0 ? $totalIncome / $income->count() : 0, 0, ',', '.') }}
                </p>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">Transactions</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $transactionsCount }}
                </p>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">Profit Margin</p>
                <p
                    class="text-lg font-semibold {{ $totalIncome > 0 ? 'text-green-600' : 'text-gray-900' }} dark:text-white">
                    {{ $totalIncome > 0 ? round(($totalNetto / $totalIncome) * 100, 1) : 0 }}%
                </p>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">Active Days</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $income->count() }}
                </p>
            </div>
        </div>
    </div>

    <!-- Tables Section -->
    @php
        $peakIncome = $income->max('total') ?: 0;
        $bestDay = $income->sortByDesc('total')->first();
    @endphp
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Daily Income Breakdown -->
        <div
            class="lg:col-span-5 bg-white dark:bg-slate-850 rounded-xl shadow-lg flex flex-col h-[32rem] overflow-hidden">
            <div class="shrink-0 px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Daily Income Breakdown</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            {{ $income->count() }} active {{ Str::plural('day', $income->count()) }} in
                            {{ $listMonth[$selectedMonth] }} {{ $selectedYear }}
                        </p>
                    </div>
                    <span
                        class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200">
                        Rp {{ number_format($totalIncome, 0, ',', '.') }}
                    </span>
                </div>
                @if ($bestDay)
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">
                        Best day &middot;
                        <span class="font-medium text-gray-700 dark:text-gray-200">
                            {{ \Carbon\Carbon::parse($bestDay['tanggal'])->format('d M') }}
                        </span>
                        &middot; Rp {{ number_format($bestDay['total'], 0, ',', '.') }}
                    </p>
                @endif
            </div>

            <div class="flex-1 min-h-0 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700">
                @forelse ($income as $index => $item)
                    @php
                        $share = $peakIncome > 0 ? max(0, min(100, ($item['total'] / $peakIncome) * 100)) : 0;
                    @endphp
                    <div wire:key='income-{{ $index }}' wire:loading.remove
                        wire:target='selectedMonth, selectedYear'
                        class="px-6 py-3 hover:bg-gray-50 dark:hover:bg-slate-900 transition-colors">
                        <div class="flex items-baseline justify-between gap-3">
                            <span class="text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($item['tanggal'])->format('d M Y') }}
                            </span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white whitespace-nowrap">
                                Rp {{ number_format($item['total'], 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="mt-2 h-1.5 w-full rounded-full bg-gray-100 dark:bg-slate-900 overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-green-400 to-green-600"
                                style="width: {{ round($share, 2) }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                        No income data available for this period
                    </div>
                @endforelse

                <!-- Loading skeleton -->
                @for ($i = 0; $i <= 5; $i++)
                    <div wire:loading.class="block" class="hidden px-6 py-3" wire:loading.class.remove="hidden"
                        wire:target='selectedMonth, selectedYear'>
                        <div class="flex items-center justify-between gap-3">
                            <div class="h-4 w-24 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                            <div class="h-4 w-20 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                        </div>
                        <div class="mt-2 h-1.5 w-full bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Technician Fees -->
        <div
            class="lg:col-span-7 bg-white dark:bg-slate-850 rounded-xl shadow-lg flex flex-col h-[32rem] overflow-hidden">
            <div class="shrink-0 px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Technician Fees</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            @if ($selectTechnician)
                                {{ count($dataFeeTechnician) }} {{ Str::plural('job', count($dataFeeTechnician)) }}
                                &middot; {{ $technician->firstWhere('id', $selectTechnician)?->name ?? 'Technician' }}
                            @else
                                Select a technician in the filters above
                            @endif
                        </p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Total Fee</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white whitespace-nowrap">
                            Rp {{ number_format($totalFeeTeknisi, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex-1 min-h-0 overflow-y-auto">
                <table class="w-full table-fixed">
                    <thead class="sticky top-0 z-10 bg-white dark:bg-slate-850">
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th
                                class="py-3 pl-6 pr-3 w-24 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400 bg-white dark:bg-slate-850">
                                Date
                            </th>
                            <th
                                class="py-3 px-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400 bg-white dark:bg-slate-850">
                                Job Detail
                            </th>
                            <th
                                class="py-3 pl-3 pr-6 w-32 text-right text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400 bg-white dark:bg-slate-850">
                                Fee
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($dataFeeTechnician as $index => $item)
                            @php
                                $sparepart = \App\Models\Product::getProductName($item['product_id']);
                            @endphp
                            <tr wire:key='fee-{{ $index }}' wire:loading.remove
                                wire:target='selectedMonth, selectedYear, selectTechnician'
                                class="hover:bg-gray-50 dark:hover:bg-slate-900 transition-colors align-top">
                                <td class="py-3 pl-6 pr-3 text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($item['created_at'])->format('d M') }}
                                    <span class="block text-xs text-gray-400 dark:text-gray-500">
                                        {{ \Carbon\Carbon::parse($item['created_at'])->format('Y') }}
                                    </span>
                                </td>
                                <td class="py-3 px-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $item['service'] }}
                                    </p>
                                    <div class="flex flex-wrap items-center gap-x-2 gap-y-1 mt-1">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $sparepart ?: 'No sparepart' }}
                                        </span>
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-mono text-gray-600 bg-gray-100 dark:bg-slate-900 dark:text-gray-300">
                                            {{ $item['order_transaction'] }}
                                        </span>
                                    </div>
                                </td>
                                <td
                                    class="py-3 pl-3 pr-6 text-sm text-right font-semibold text-gray-900 dark:text-white whitespace-nowrap">
                                    Rp {{ number_format($item['fee_teknisi'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-12 px-6 text-center">
                                    <svg class="w-10 h-10 mx-auto text-gray-300 dark:text-gray-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                                        @if ($selectTechnician)
                                            No fee data for selected technician in this period
                                        @else
                                            Please select a technician to view fee data
                                        @endif
                                    </p>
                                </td>
                            </tr>
                        @endforelse

                        <!-- Loading skeleton -->
                        @for ($i = 0; $i <= 5; $i++)
                            <tr wire:loading.class="table-row" class="hidden" wire:loading.class.remove="hidden"
                                wire:target='selectedMonth, selectedYear, selectTechnician'>
                                <td class="py-3 pl-6 pr-3">
                                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                                </td>
                                <td class="py-3 px-3">
                                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                                    <div class="h-3 w-2/3 mt-2 bg-gray-200 dark:bg-gray-700 rounded animate-pulse">
                                    </div>
                                </td>
                                <td class="py-3 pl-3 pr-6">
                                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Export Button -->
    <div class="flex justify-end">
        <button wire:click="exportReport" wire:loading.attr="disabled"
            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
            <svg wire:loading.remove wire:target="exportReport" class="w-5 h-5 mr-2" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
            </svg>
            <svg wire:loading wire:target="exportReport" class="animate-spin h-5 w-5 mr-2"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                    stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <span wire:loading.remove wire:target="exportReport">Export Report</span>
            <span wire:loading wire:target="exportReport">Exporting...</span>
        </button>
    </div>
</div>
