<div class="w-full px-6 py-4 mx-auto flex flex-col h-screen max-h-screen">
    <div
        class="flex items-center justify-between bg-white rounded-2xl border-o border-transparent border-solid p-6 shadow-xl bg-clip-border">
        <div class="mb-0 border-b-0 border-solid ">
            <h5 class="mb-1 font-serif">Hello Admin, </h5>
            <p class="mb-0 text-sm leading-normal dark:text-white dark:opacity-60 font-serif">
                {{ \Carbon\Carbon::now()->format('l, d M Y') }}
            </p>
        </div>
        <div class="relative">
            <ul class="relative flex flex-wrap gap-x-3 p-1 list-none bg-transparent rounded-xl">
                <li class="z-90 flex-auto text-center transition-all">
                    <button
                        class="z-90 block w-full px-4 py-1 mb-0 transition-all border-0 rounded-lg ease-in-out  text-slate-700 {{ $tabActive === 'point-of-sales' ? 'bg-primary text-white' : '' }}"
                        nav-link wire:click="changeActiveTab('point-of-sales')">
                        <span class="ml-1">Cashier</span>
                    </button>
                </li>
                <li class="z-90 flex-auto text-center transition-all">
                    <button
                        class="z-90 block w-full px-4 py-1 mb-0 transition-all border-0 rounded-lg ease-in-out  text-slate-700 {{ $tabActive === 'transaction-process' ? 'bg-primary text-white' : '' }}"
                        nav-link wire:click="changeActiveTab('transaction-process')">
                        <span class="ml-1">Transaction Process</span>
                    </button>
                </li>
                <li class="z-90 flex-auto text-center transition-all">
                    <button
                        class="z-90 block w-full px-4 py-1 mb-0 transition-all border-0 rounded-lg ease-in-out  text-slate-700 {{ $tabActive === 'transaction-cancel' ? 'bg-primary text-white' : '' }}"
                        nav-link wire:click="changeActiveTab('transaction-cancel')">
                        <span class="ml-1">Transaction Cancel</span>
                    </button>
                </li>
                <li class="z-90 flex-auto text-center transition-all">
                    <button
                        class="z-90 block w-full px-4 py-1 mb-0 transition-all border-0 rounded-lg ease-in-out bg-danger text-white"
                        nav-link wire:click="changeActiveTab('transaction-complaint')">
                        <span class="ml-1">Complaint</span>
                    </button>
                </li>
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
        <div class="{{ $tabActive === 'point-of-sales' ? 'block' : 'hidden' }} w-full">
            @livewire('dashboard.point-of-sales', key('cashier{{ time() }}'))
        </div>
        <div class="{{ $tabActive === 'transaction-process' ? 'block' : 'hidden' }} w-full">
            @livewire('dashboard.transaction-process', key('process{{ time() }}'))
        </div>
        <div class="{{ $tabActive === 'transaction-cancel' ? 'block' : 'hidden' }} w-full">
            @livewire('dashboard.transaction-cancel', key('cancel{{ time() }}'))
        </div>
        <div class="{{ $tabActive === 'transaction-complaint' ? 'block' : 'hidden' }} w-full">
            @livewire('dashboard.transaction-complaint', key('complaint{{ time() }}'))
        </div>
    </div>

    <script>
        // Listen for Livewire events
        window.addEventListener('refresh-child-component', event => {
            // Trigger a re-render of the specified child component
            Livewire.emit('refreshComponent');
        });
    </script>

</div>
