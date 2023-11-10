<div class="flex flex-col md:flex-row gap-4  justify-center w-full">
    <div
        class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border h-full w-full">
        <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
            <h6 class="dark:text-white">Income Table</h6>
        </div>
        <div class="flex-auto p-6">
            <div class="p-0 overflow-x-auto">
                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
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
                        @foreach ($income as $item)
                            <tr>
                                <td
                                    class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                    <span
                                        class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                        {{ $item->tanggal }}
                                    </span>
                                </td>
                                <td
                                    class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                    <span
                                        class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                        Rp {{ number_format($item->total) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div
        class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border h-full w-full">
        <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
            <h6 class="dark:text-white">Fee Technician Table</h6>
        </div>

        <div class="relative">
            <div class="w-full max-w-xs mx-auto">
                <label for="countries" class="block text-sm font-medium text-gray-700">Select Technician</label>
                <select wire:model='selectTechnician'
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md bg-white">
                    <option value="">Please select</option>
                    @foreach ($technician as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex-auto p-6">
            <div class="relative w-full">
                <div wire:loading.flex wire:target='selectTechnician'
                    class="flex flex-grow min-w-0 flex-col break-words items-center flex-1 mt-6 h-full">
                    <div class="flex flex-1 items-center content-center">
                        <div class="inline-block h-6 w-6 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                            role="status">
                            <span
                                class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                        </div>
                    </div>

                </div>
            </div>
            @if (count($dataFeeTechnician) > 0)
                <div class="p-0 overflow-x-auto" wire:loading.remove wire:target='selectTechnician'>
                    <table
                        class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                        <thead class="align-bottom">
                            <tr>
                                <th
                                    class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Service
                                </th>
                                <th
                                    class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataFeeTechnician as $item)
                                <tr>
                                    <td
                                        class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span
                                            class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                            {{ $item->service }}
                                        </span>
                                    </td>
                                    <td
                                        class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span
                                            class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                            Rp {{ number_format($item->fee_teknisi) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
