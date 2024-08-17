<div class="w-full px-6 py-4 mx-auto flex flex-col h-screen">

    <div class="flex justify-between items-center ">
        <div class=" mb-0 border-b-0 border-solid ">
            <h5 class="mb-1 font-serif">Technician</h5>
            <p class="mb-0 text-sm leading-normal dark:text-white dark:opacity-60 font-serif">
                {{ \Carbon\Carbon::now()->format('l, d M Y') }}
            </p>
        </div>
        <div class="flex items-center md:ml-auto md:pr-4">

        </div>
    </div>


    <div class="flex flex-wrap -mx-3 mt-6 justify-center">
        <div class="flex-none {{ $isAdd || $isEdit ? 'w-8/12' : 'w-full' }} max-w-full px-3">
            <div
                class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border h-full">
                <div
                    class="block md:flex w-full justify-between items-center p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <div class="pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <h6 class="dark:text-white">Technician Table</h6>
                    </div>
                    <button type="button" wire:click='setShowAdd'
                        class="px-8 py-2 mb-4 text-xs font-bold leading-normal text-center text-white capitalize transition-all ease-in rounded-lg shadow-md bg-slate-700 bg-150 hover:shadow-xs hover:-translate-y-px"
                        data-te-ripple-color="light">
                        <div wire:loading wire:target='setShowAdd'>
                            <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                role="status">
                                <span
                                    class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                            </div>
                        </div>
                        {{ $isAdd ? 'Batal' : 'Tambah' }}
                    </button>
                </div>

                <div class="flex-auto px-0 pt-0 pb-4">
                    <div class="p-2 overflow-x-auto">
                        <table
                            class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                            <thead class="align-bottom">
                                <tr>
                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        No</th>
                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Nama</th>
                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Kode</th>
                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Persentase</th>
                                    <th
                                        class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $index => $item)
                                    <tr>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                {{ $index + 1 }}
                                            </span>
                                        </td>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                {{ $item->name }}
                                            </span>
                                        </td>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                {{ $item->kode }}
                                            </span>
                                        </td>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span
                                                class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                {{ $item->percent_fee !== null ? $item->percent_fee . '%' : '-' }}
                                            </span>
                                        </td>
                                        <td
                                            class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <button type="button" wire:click='edit({{ $item->id }})'
                                                class="inline-block px-3 py-2 text-xs mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-primary leading-normal  ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md">
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
                                            <button type="button"
                                                wire:click="$emit('triggerDelete',{{ $item->id }})"
                                                class="inline-block px-3 py-2 text-xs mr-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-red-600 leading-normal  ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 hover:-translate-y-px active:opacity-85 hover:shadow-md">
                                                <i class="fas fa-trash-alt" wire:loading.remove
                                                    wire:target='delete({{ $item->id }})'></i>

                                                <div wire:loading wire:target='delete({{ $item->id }})'>
                                                    <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                                        role="status">
                                                        <span
                                                            class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                                                    </div>
                                                </div>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                        <div class="mt-4 px-4">
                            {{ $data->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($isAdd || $isEdit)
            <div class="flex-none w-4/12 max-w-full px-3">
                <div
                    class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                    <div
                        class="block md:flex w-full justify-between items-center p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <div class="pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                            <h6 class="dark:text-white">{{ $isAdd ? 'Add' : 'Edit' }} Technician</h6>
                        </div>
                    </div>

                    <div class="w-full p-6">
                        <form wire:submit.prevent="{{ $isAdd ? 'store' : 'update' }}">
                            <div class="relative mb-8">
                                <x-ui.input-default wire:model="name" label="Name" />
                                @error('name')
                                    <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror

                            </div>
                            <div class="relative mb-8">
                                <x-ui.input-default wire:model="kode" label="Kode" />
                                @error('kode')
                                    <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror

                            </div>
                            <div class="relative mb-8">
                                <x-ui.input-default wire:model="percent_fee" label="Persentase" />
                                @error('percent_fee')
                                    <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror

                            </div>

                            <x-ui.button type="submit" title="Submit" color="primary" wireLoading
                                formAction="{{ $isAdd ? 'store' : 'update' }}" />
                        </form>
                    </div>
                </div>
            </div>
        @endif

    </div>

</div>

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            @this.on('triggerDelete', id => {
                Swal.fire({
                    title: 'Are You Sure?',
                    html: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                }).then((result) => {
                    if (result.value) {
                        @this.call('delete', id)
                    }
                });
            });
        })
    </script>
@endpush
