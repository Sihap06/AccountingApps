<div class="flex flex-wrap -mx-3 custom-height" wire:init="loadLogs">
    <div class="flex-none w-full max-w-full px-3 h-full">
        <div
            class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border h-full">
            <div class="flex-auto px-4 pt-0 mt-4 pb-4 overflow-auto h-full">
                <div class="p-0">
                    <table
                        class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                        <thead class="align-bottom">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left font-bold uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    No</th>
                                <th
                                    class="px-6 py-3 text-left font-bold uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Date</th>
                                <th
                                    class="px-6 py-3 text-left font-bold uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    User</th>
                                <th
                                    class="px-6 py-3 text-left font-bold uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Product</th>
                                <th
                                    class="px-6 py-3 text-left font-bold uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Activity</th>

                            </tr>
                        </thead>
                        <tbody>
                            @if (!$readyToLoad)
                                @for ($i = 0; $i < 5; $i++)
                                    <tr>
                                        <td class="p-2 px-4 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <div class="h-5 w-8 rounded bg-gray-200 animate-pulse"></div>
                                        </td>
                                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <div class="h-5 w-32 rounded bg-gray-200 animate-pulse"></div>
                                        </td>
                                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <div class="h-5 w-24 rounded bg-gray-200 animate-pulse"></div>
                                        </td>
                                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <div class="h-5 w-32 rounded bg-gray-200 animate-pulse"></div>
                                        </td>
                                        <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <div class="h-5 w-48 rounded bg-gray-200 animate-pulse"></div>
                                        </td>
                                    </tr>
                                @endfor
                            @else
                            @foreach ($data as $index => $item)
                                <tr wire:key='log-inv-{{ $index }}'>
                                    <td
                                        class="p-2 px-4 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span
                                            class="text-xs  leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                            {{ $index + 1 }}
                                        </span>
                                    </td>
                                    <td
                                        class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span
                                            class="text-xs  leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                            {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i:s') }}
                                        </span>
                                    </td>
                                    <td
                                        class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span
                                            class="text-xs  leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                            {{ $item->user }}
                                        </span>
                                    </td>
                                    <td
                                        class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span
                                            class="text-xs  leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                            {{ $item->product }}
                                        </span>
                                    </td>
                                    <td
                                        class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span
                                            class="text-xs  leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                            @if ($item->activity === 'store' || $item->activity === 'create (pending)')
                                                {{ $item->activity === 'create (pending)' ? 'Added new item (pending)' : 'Added new item' }}
                                            @elseif($item->activity === 'delete' || $item->activity === 'delete (pending)')
                                                {{ $item->activity === 'delete (pending)' ? 'Deleted item (pending)' : 'Deleted item' }}
                                            @elseif($item->activity === 'stock_update')
                                                Stock Update
                                            @else
                                                <div class="{{ strpos($item->activity, '(pending)') !== false ? 'text-amber-500' : '' }}">
                                                    {{ strpos($item->activity, '(pending)') !== false ? 'Update (pending):' : '' }}
                                                    <ul>
                                                        @if ($item->old_name !== null && $item->new_name !== null)
                                                            <li>
                                                                Changed item name from {{ $item->old_name }}
                                                                to
                                                                {{ $item->new_name }}
                                                            </li>
                                                        @endif
                                                        @if ($item->old_price !== null && $item->new_price !== null)
                                                            <li>
                                                                Changed item price from {{ $item->old_price }}
                                                                to
                                                                {{ $item->new_price }}
                                                            </li>
                                                        @endif
                                                        @if ($item->old_stok !== null && $item->new_stok !== null)
                                                            <li>
                                                                Updated item stock from {{ $item->old_stok }}
                                                                to
                                                                {{ $item->new_stok }}
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                            @endif

                        </tbody>

                    </table>

                    @if ($readyToLoad && $hasMorePages)
                        <div x-data="{
                            observe() {
                                const observer = new IntersectionObserver((entries) => {
                                    entries.forEach(entry => {
                                        if (entry.isIntersecting) {
                                            @this.call('loadMore');
                                        }
                                    });
                                });
                                observer.observe(this.$el);
                            }
                        }" x-init="observe" class="p-4 text-center">
                            <div wire:loading wire:target="loadMore"
                                class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite] text-primary">
                                <span class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                            </div>
                            <span wire:loading.remove wire:target="loadMore" class="text-sm text-gray-500">Scroll to see more...</span>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>

</div>
