<div class="w-full px-6 py-4 mx-auto flex flex-col h-screen max-h-screen">
    <div
        class="flex items-center justify-between rounded-2xl border-o border-transparent border-solid p-6 shadow-xl bg-clip-border">
        <div class="mb-0 border-b-0 border-solid ">
            <h5 class="mb-1 font-serif">Reporting</h5>
            <p class="mb-0 text-sm leading-normal dark:text-white dark:opacity-60 font-serif">
                {{ \Carbon\Carbon::now()->format('l, d M Y') }}
            </p>
        </div>
        <div class="relative w-6/12">
            <ul class="relative flex flex-wrap gap-x-3 p-1 list-none bg-transparent rounded-xl">
                <li class="z-30 flex-auto text-center transition-all">
                    <button
                        class="z-30 block w-full px-0 py-1 mb-0 transition-all border-0 rounded-lg ease-in-out bg-inherit text-slate-700 {{ $tabActive === 'transaction' ? 'bg-primary text-white' : '' }}"
                        nav-link wire:click="changeActiveTab('transaction')">
                        <span class="ml-1">Transaction</span>
                    </button>
                </li>
                <li class="z-30 flex-auto text-center transition-all">
                    <button
                        class="z-30 block w-full px-0 py-1 mb-0 transition-all border-0 rounded-lg ease-in-out bg-inherit text-slate-700 {{ $tabActive === 'expenditure' ? 'bg-primary text-white' : '' }}"
                        nav-link wire:click="changeActiveTab('expenditure')">
                        <span class="ml-1">Expenditure</span>
                    </button>
                </li>
                @if (auth()->user()->role !== 'sysadmin')
                    <li class="z-30 flex-auto text-center transition-all">
                        <button
                            class="z-30 block w-full px-0 py-1 mb-0 transition-all border-0 rounded-lg ease-in-out bg-inherit text-slate-700 {{ $tabActive === 'income' ? 'bg-primary text-white' : '' }}"
                            nav-link wire:click="changeActiveTab('income')">
                            <span class="ml-1">Income & Fee</span>
                        </button>
                    </li>
                    <li class="z-30 flex-auto text-center transition-all">
                        <button
                            class="z-30 block w-full px-0 py-1 mb-0 transition-all border-0 rounded-lg ease-in-out bg-inherit text-slate-700 {{ $tabActive === 'export' ? 'bg-primary text-white' : '' }}"
                            nav-link wire:click="changeActiveTab('export')">
                            <span class="ml-1">Export</span>
                        </button>
                    </li>
                @endif
            </ul>
        </div>
    </div>

    <div wire:loading.flex wire:target='changeActiveTab'
        class="flex flex-grow border-black/12.5 dark:bg-slate-850 dark:shadow-dark-xl shadow-xl z-20 min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border items-center flex-1 mt-6 h-full">
        <div class="flex flex-1 items-center content-center">
            <div class="inline-block h-6 w-6 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                role="status">
                <span
                    class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
            </div>
        </div>

    </div>

    <div wire:loading.remove wire:target='changeActiveTab' class="flex flex-grow mt-6">
        @if ($tabActive === 'transaction')
            <div class="w-full">
                @livewire('dashboard.reporting.transaction', key('transaction'), ['id' => $selectedId])
            </div>
        @elseif ($tabActive === 'expenditure')
            <div class=" w-full">
                @livewire('dashboard.reporting.expenditure', key('expenditure'))
            </div>
        @elseif($tabActive === 'income')
            <div class="w-full">
                @livewire('dashboard.reporting.income', key('income'))
            </div>
        @else
            <div class="w-full">
                @livewire('dashboard.reporting.export', key('export'))
            </div>
        @endif



    </div>

    <script>
        // Listen for Livewire events
        window.addEventListener('refresh-child-component', event => {
            // Trigger a re-render of the specified child component
            Livewire.emit('refreshComponent');
        });
    </script>

</div>
