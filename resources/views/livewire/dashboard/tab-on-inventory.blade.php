<div class="w-full px-6 py-4 mx-auto flex flex-col">
    <div
        class="flex flex-col md:flex-row items-center justify-between bg-white rounded-2xl border-0 border-transparent border-solid p-6 shadow-xl bg-clip-border gap-4">
        <div class="mb-0 border-b-0 border-solid">
            <h5 class="mb-1 font-serif">
                @if($tabActive === 'inventory')
                    Inventaris
                @else
                    Riwayat Update Stok
                @endif
            </h5>
            <p class="mb-0 text-sm leading-normal dark:text-white dark:opacity-60 font-serif">
                {{ \Carbon\Carbon::now()->format('l, d M Y') }}
            </p>
        </div>
        <div class="relative w-full md:w-1/2">
            <ul class="relative flex flex-wrap gap-x-3 p-1 list-none bg-transparent rounded-xl">
                <li class="z-90 flex-auto text-center transition-all">
                    <button
                        class="z-90 block w-full px-0 py-1 mb-0 transition-all border-0 rounded-lg ease-in-out text-slate-700 {{ $tabActive === 'inventory' ? 'bg-primary text-white' : '' }}"
                        wire:click="changeActiveTab('inventory')">
                        <span class="ml-1">Inventaris</span>
                    </button>
                </li>
                <li class="z-90 flex-auto text-center transition-all">
                    <button
                        class="z-90 block w-full px-0 py-1 mb-0 transition-all border-0 rounded-lg ease-in-out text-slate-700 {{ $tabActive === 'stock_update_history' ? 'bg-primary text-white' : '' }}"
                        wire:click="changeActiveTab('stock_update_history')">
                        <span class="ml-1">Riwayat Update Stok</span>
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <div class="flex flex-grow mt-6">
        <div class="w-full" wire:key="tab-{{ $tabActive }}">
            @if($tabActive === 'inventory')
                @livewire('dashboard.inventory')
            @elseif($tabActive === 'stock_update_history')
                @livewire('dashboard.stock-update-history')
            @endif
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
            height: calc(100vh - 160px);
        }
    </style>
@endpush
