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
                                    Tanggal</th>
                                <th
                                    class="px-6 py-3 text-left font-bold uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    User</th>
                                <th
                                    class="px-6 py-3 text-left font-bold uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Order Transaction</th>
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
                                            {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}
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
                                            {{ $item->order_transaction }}
                                        </span>
                                    </td>
                                    <td
                                        class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span
                                            class="text-xs  leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                            <ul>
                                                @if ($item->old_customer !== null && $item->new_customer !== null)
                                                    <li>
                                                        Mengganti customer dari <span
                                                            class="uppercase font-semibold">{{ $item->old_customer }}</span>
                                                        menjadi
                                                        <span
                                                            class="uppercase font-semibold">{{ $item->new_customer }}</span>
                                                    </li>
                                                @endif
                                                @if ($item->old_payment_method !== null && $item->new_payment_method !== null)
                                                    <li>
                                                        Mengganti metode pembayaran dari <span
                                                            class="uppercase font-semibold">{{ $item->old_payment_method }}</span>
                                                        menjadi
                                                        <span
                                                            class="uppercase font-semibold">{{ $item->new_payment_method }}</span>
                                                    </li>
                                                @endif
                                                @if ($item->old_tanggal !== null && $item->new_tanggal !== null)
                                                    <li>
                                                        Mengubah tanggal transaksi dari <span
                                                            class="uppercase font-semibold">{{ $item->old_tanggal }}</span>
                                                        menjadi
                                                        <span
                                                            class="uppercase font-semibold">{{ $item->new_tanggal }}</span>
                                                    </li>
                                                @endif
                                                @if ($item->old_service !== null && $item->new_service !== null)
                                                    <li>
                                                        Mengubah service dari <span
                                                            class="uppercase font-semibold">{{ $item->old_service }}</span>
                                                        menjadi
                                                        <span
                                                            class="uppercase font-semibold">{{ $item->new_service }}</span>
                                                    </li>
                                                @endif
                                                @if ($item->old_biaya !== null && $item->new_biaya !== null)
                                                    <li>
                                                        Mengubah biaya dari <span
                                                            class="uppercase font-semibold">{{ number_format($item->old_biaya) }}</span>
                                                        menjadi
                                                        <span
                                                            class="uppercase font-semibold">{{ number_format($item->new_biaya) }}</span>
                                                    </li>
                                                @endif
                                                @if ($item->old_teknisi !== null && $item->new_teknisi !== null)
                                                    <li>
                                                        Mengubah teknisi dari <span
                                                            class="uppercase font-semibold">{{ $item->old_teknisi }}</span>
                                                        menjadi
                                                        <span
                                                            class="uppercase font-semibold">{{ $item->new_teknisi }}</span>
                                                    </li>
                                                @endif
                                                @if ($item->old_sparepart !== null && $item->new_sparepart !== null)
                                                    <li>
                                                        Mengubah sparepart dari <span
                                                            class="uppercase font-semibold">{{ $item->old_sparepart }}</span>
                                                        menjadi
                                                        <span
                                                            class="uppercase font-semibold">{{ $item->new_sparepart }}</span>
                                                    </li>
                                                @endif
                                                @if ($item->old_teknisi !== null && $item->new_sparepart !== null)
                                                    <li>
                                                        Menghapus teknisi <span
                                                            class="uppercase font-semibold">{{ $item->old_teknisi }}</span>
                                                        dan menambahkan
                                                        sparepart
                                                        <span
                                                            class="uppercase font-semibold">{{ $item->new_sparepart }}</span>
                                                    </li>
                                                @endif
                                                @if ($item->new_teknisi !== null && $item->old_sparepart !== null)
                                                    <li>
                                                        Menghapus sparepart <span
                                                            class="uppercase font-semibold">{{ $item->old_sparepart }}</span>
                                                        dan menambahkan
                                                        teknisi
                                                        <span
                                                            class="uppercase font-semibold">{{ $item->new_teknisi }}</span>
                                                    </li>
                                                @endif
                                            </ul>
                                        </span>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>

                    </table>

                </div>
            </div>

        </div>
    </div>

</div>
