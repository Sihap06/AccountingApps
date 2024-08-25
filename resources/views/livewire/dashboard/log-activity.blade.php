<div class="w-full px-6 py-4 mx-auto flex flex-col">
    <div
        class="flex items-center justify-between bg-white rounded-2xl border-o border-transparent border-solid p-6 shadow-xl bg-clip-border">
        <div class="mb-0 border-b-0 border-solid ">
            <h5 class="mb-1 font-serif">Catatan Aktivitas</h5>
            <p class="mb-0 text-sm leading-normal dark:text-white dark:opacity-60 font-serif">
                {{ \Carbon\Carbon::now()->format('l, d M Y') }}
            </p>
        </div>
        <div class="relative w-1/2">
        </div>
    </div>

    <div class="flex flex-grow mt-6">
        <div class="w-full">

            <div class="flex flex-wrap -mx-3 custom-height">
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
                                        @foreach ($data as $index => $item)
                                            <tr wire:key='{{ $index }}' wire:loading.remove
                                                wire:target='gotoPage, previousPage, nextPage, searchTerm'>
                                                <td
                                                    class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span
                                                        class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                        {{ $index + 1 }}
                                                    </span>
                                                </td>
                                                <td
                                                    class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span
                                                        class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                        {{ $item->user }}
                                                    </span>
                                                </td>
                                                <td
                                                    class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span
                                                        class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                        {{ $item->product }}
                                                    </span>
                                                </td>
                                                <td
                                                    class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span
                                                        class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                        @if ($item->activity === 'store')
                                                            Add new product
                                                        @elseif($item->activity === 'delete')
                                                            Delete product
                                                        @else
                                                            <ul>
                                                                @if ($item->old_name !== null && $item->new_name !== null)
                                                                    <li>
                                                                        Change product name from {{ $item->old_name }}
                                                                        to
                                                                        {{ $item->new_name }}
                                                                    </li>
                                                                @endif
                                                                @if ($item->old_price !== null && $item->new_price !== null)
                                                                    <li>
                                                                        Change product price from {{ $item->old_price }}
                                                                        to
                                                                        {{ $item->new_price }}
                                                                    </li>
                                                                @endif
                                                                @if ($item->old_stok !== null && $item->new_stok !== null)
                                                                    <li>
                                                                        Update product stok from {{ $item->old_stok }}
                                                                        to
                                                                        {{ $item->new_stok }}
                                                                    </li>
                                                                @endif
                                                            </ul>
                                                        @endif
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach

                                        {{-- @for ($i = 0; $i <= 10; $i++)
                                <tr wire:loading.class="table-row" class="hidden" wire:loading.class.remove="hidden"
                                    wire:target='gotoPage, previousPage, nextPage, searchTerm, loadMore'>
                                    <td
                                        class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <div class="mb-2 h-5 w-full rounded overflow-hidden relative bg-gray-200">
                                        </div>
                                    </td>
                                    <td
                                        class="p-2 text-left align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <div class="mb-2 h-5 w-full rounded overflow-hidden relative bg-gray-200">
                                        </div>
                                    </td>
                                    <td
                                        class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <div class="mb-2 h-5 w-full rounded overflow-hidden relative bg-gray-200">
                                        </div>
                                    </td>
                                    <td
                                        class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <div class="mb-2 h-5 w-full rounded overflow-hidden relative bg-gray-200">
                                        </div>
                                    </td>
                                    <td
                                        class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <div class="mb-2 h-5 w-full rounded overflow-hidden relative bg-gray-200">
                                        </div>
                                    </td>
                                    <td
                                        class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <div class="mb-2 h-5 w-full rounded overflow-hidden relative bg-gray-200">
                                        </div>
                                    </td>
                                </tr>
                            @endfor --}}
                                    </tbody>

                                </table>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
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
