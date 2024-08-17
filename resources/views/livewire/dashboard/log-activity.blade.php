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
                                            {{ ($data->currentPage() - 1) * $data->perPage() + $index + 1 }}
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
                                                            Change product name from {{ $item->old_name }} to
                                                            {{ $item->new_name }}
                                                        </li>
                                                    @endif
                                                    @if ($item->old_price !== null && $item->new_price !== null)
                                                        <li>
                                                            Change product price from {{ $item->old_price }} to
                                                            {{ $item->new_price }}
                                                        </li>
                                                    @endif
                                                    @if ($item->old_stok !== null && $item->new_stok !== null)
                                                        <li>
                                                            Add product stok {{ $item->new_stok - $item->old_stok }}
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
