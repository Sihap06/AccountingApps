<div class="w-full px-6 py-4 mx-auto flex flex-col ">
    <div
        class="flex items-center justify-between bg-white rounded-2xl border-o border-transparent border-solid p-6 shadow-xl bg-clip-border">
        <div class="mb-0 border-b-0 border-solid ">
            <h5 class="mb-1 font-serif">{{ $tabActive === 'inventory' ? 'Inventory' : 'Catatan Aktivitas' }}</h5>
            <p class="mb-0 text-sm leading-normal dark:text-white dark:opacity-60 font-serif">
                {{ \Carbon\Carbon::now()->format('l, d M Y') }}
            </p>
        </div>
        <div class="relative w-1/2">
            <ul class="relative flex flex-wrap gap-x-3 p-1 list-none bg-transparent rounded-xl">
                <li class="z-90 flex-auto text-center transition-all">
                    <button
                        class="z-90 block w-full px-0 py-1 mb-0 transition-all border-0 rounded-lg ease-in-out  text-slate-700 {{ $tabActive === 'inventory' ? 'bg-primary text-white' : '' }}"
                        nav-link wire:click="changeActiveTab('inventory')">
                        <span class="ml-1">Inventory</span>
                    </button>
                </li>
                <li class="z-90 flex-auto text-center transition-all">
                    <button
                        class="z-90 block w-full px-0 py-1 mb-0 transition-all border-0 rounded-lg ease-in-out  text-slate-700 {{ $tabActive === 'log_activity' ? 'bg-primary text-white' : '' }}"
                        nav-link wire:click="changeActiveTab('log_activity')">
                        <span class="ml-1">Catatan Aktivitas</span>
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <div wire:loading.flex wire:target='changeActiveTab'
        class="flex flex-grow border-black/12.5 dark:bg-slate-850 dark:shadow-dark-xl shadow-xl z-20 min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border items-center mt-6 custom-height">
        <div class="flex flex-1 items-center content-center">
            <div class="inline-block h-6 w-6 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                role="status">
                <span
                    class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
            </div>
        </div>

    </div>

    <div wire:loading.remove wire:target='changeActiveTab' class="flex flex-grow mt-6">
        <div class="{{ $tabActive === 'inventory' ? 'block' : 'hidden' }} w-full">
            @livewire('dashboard.inventory', key('inventory'))
        </div>
        <div class="{{ $tabActive === 'log_activity' ? 'block' : 'hidden' }} w-full">
            @livewire('dashboard.log-activity', key('log_activity'))
        </div>
    </div>

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
            height: calc(100vh - 110px);
        }
    </style>
@endpush
