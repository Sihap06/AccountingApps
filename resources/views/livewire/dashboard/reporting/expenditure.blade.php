<div class="flex w-full gap-x-4">
    <div
        class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border transition-all h-full {{ $isAdd || $isEdit ? 'w-8/12' : 'w-full' }}">
        <div class="flex justify-between p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
            <h6 class="dark:text-white">Expenditure Table</h6>
            <button type="button" wire:click='setShowAdd'
                class="px-8 py-2 mb-4 text-xs font-bold leading-normal text-center text-white capitalize transition-all ease-in rounded-lg shadow-md bg-slate-700 bg-150 hover:shadow-xs hover:-translate-y-px">
                <div wire:loading wire:target='setShowAdd'>
                    <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                        role="status">
                        <span
                            class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                    </div>
                </div>
                {{ $isAdd || $isEdit ? 'Batal' : 'Tambah' }}
            </button>
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
                                Jenis
                            </th>
                            <th
                                class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                Total
                            </th>
                            <th
                                class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-collapse border-solid shadow-none dark:border-white/40 dark:text-white tracking-none whitespace-nowrap text-slate-400 opacity-70">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td
                                    class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                    <span
                                        class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td
                                    class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                    <span
                                        class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                        {{ $item->jenis }}
                                    </span>
                                </td>
                                <td
                                    class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                    <span
                                        class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                        Rp {{ number_format($item->total) }}
                                    </span>
                                </td>
                                <td
                                    class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                    <button @if ($isAdd) disabled @endif type="button"
                                        wire:click='edit({{ $item->id }})'
                                        class="inline-block px-3 py-2 text-xs mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-primary leading-normal  ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md disabled:cursor-not-allowed">
                                        <i class="fas fa-edit" wire:loading.remove
                                            wire:target='edit({{ $item->id }})'></i>
                                        <div wire:loading wire:target='edit({{ $item->id }})'>
                                            <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                                role="status">
                                                <span
                                                    class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                                            </div>
                                        </div>
                                    </button>

                                    <form wire:submit.prevent='delete({{ $item->id }})' id="formDelete" hidden>
                                        <input type="submit" value="Submit" hidden>
                                    </form>

                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @if ($isAdd || $isEdit)
        <div
            class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border h-full w-4/12 transition-all">
            <div
                class="flex justify-between p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <h6 class="dark:text-white">{{ $isAdd ? 'Add' : 'Edit' }} Expenditure</h6>
            </div>

            <div class="w-full p-6">
                <form wire:submit.prevent="{{ $isAdd ? 'store' : 'update' }}">
                    <div class="relative mb-8">
                        <x-ui.input-default wire:model="tanggal" label="Tanggal" type="date" />

                        @error('tanggal')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                        @enderror

                    </div>
                    <div class="relative mb-8">
                        <x-ui.input-default wire:model="jenis" label="Jenis" />
                        @error('jenis')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="relative mb-8">
                        <x-ui.input-default wire:model="total" id="total" label="Total" x-data="{
                            formatNumber: function(event) {
                                const input = event.target;
                                const value = input.value.replace(/\D/g, ''); // Remove non-numeric characters
                                input.value = new Intl.NumberFormat('en-US').format(value);
                            }
                        }"
                            x-on:input="formatNumber($event)" />
                        @error('total')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <x-ui.button type="submit" title="Submit" color="primary" wireLoading
                        formAction="{{ $isAdd ? 'store' : 'update' }}" />
                </form>
            </div>
        </div>
    @endif
</div>
