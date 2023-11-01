<div class="w-full px-6 py-4 mx-auto flex flex-col h-screen">
    <div class="flex justify-between items-center ">
        <div class=" mb-0 border-b-0 border-solid ">
            <h5 class="mb-1 font-serif">Hello Admin, </h5>
            <p class="mb-0 text-sm leading-normal dark:text-white dark:opacity-60 font-serif">
                {{ \Carbon\Carbon::now()->format('l, d M Y') }}
            </p>
        </div>
        <div class="flex items-center md:ml-auto md:pr-4">

        </div>
    </div>
    <div class="flex justify-center">
        {{-- @dd($inventory) --}}
        <div class="w-full max-w-full mt-6 md:w-8/12">
            <div
                class=" border-black/12.5 dark:bg-slate-850 dark:shadow-dark-xl shadow-xl z-20 min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border items-center flex-1 mt-6">
                <div class="border-black/12.5 rounded-t-2xl border-b-0 border-solid p-6 pb-0">
                    <div class="flex items-center">
                        <p class="mb-0 dark:text-white/80">Transaction</p>
                    </div>
                </div>
                <div class="flex-auto p-6 mt-4">
                    <form wire:submit.prevent="submit">
                        <div class="flex flex-wrap -mx-3">
                            <div class="mb-8 w-full max-w-full px-3 shrink-0 md:flex-0">
                                <div wire:ignore>
                                    <x-ui.input label="Service" wire:model="service" id="service" />
                                </div>
                                @error('service')
                                    <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-8 w-full max-w-full px-3 shrink-0 md:flex-0">
                                <div wire:ignore>
                                    <x-ui.input label="Biaya" wire:model="biaya" id="biaya" x-data="{
                                        formatNumber: function(event) {
                                            const input = event.target;
                                            const value = input.value.replace(/\D/g, ''); // Remove non-numeric characters
                                            input.value = new Intl.NumberFormat('en-US').format(value);
                                        }
                                    }"
                                        x-on:input="formatNumber($event)" />
                                </div>
                                @error('biaya')
                                    <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-8 w-full max-w-full px-3 shrink-0 md:flex-0">
                                <div wire:ignore>
                                    <x-ui.select label="Modal" wire:model='modal' id="modal" size="lg">
                                        <option value=""></option>
                                        <option value="ya">Dengan Modal</option>
                                        <option value="tidak">Tanpa Modal</option>
                                    </x-ui.select>
                                </div>
                                @error('modal')
                                    <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-8 w-full max-w-full px-3 shrink-0 md:flex-0">
                                <div wire:ignore>
                                    <x-ui.select label="Teknisi" wire:model="technical_id" id="teknisi" search
                                        size="lg">
                                        <option value=""></option>
                                        @foreach ($technician as $value)
                                            <option value="{{ $value->id }}">{{ $value->name }}
                                            </option>
                                        @endforeach
                                    </x-ui.select>
                                </div>
                                @error('technical_id')
                                    <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-8 w-full max-w-full px-3 shrink-0 md:flex-0">
                                <div wire:ignore>
                                    <x-ui.select label="Sparepart" wire:model="product_id" id="sparepart" search
                                        size="lg">
                                        <option value=""></option>
                                        @foreach ($inventory as $index => $value)
                                            <option value="{{ $index }}">{{ $value }}
                                            </option>
                                        @endforeach
                                    </x-ui.select>
                                </div>
                                @error('product_id')
                                    <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-8 w-full max-w-full px-3 shrink-0 md:flex-0">
                                <div wire:ignore>
                                    <x-ui.select label="Metode Pembayaran" wire:model="metode_pembayaran"
                                        id="metode_pembayaran" size="lg">
                                        <option value=""></option>
                                        <option value="bca">BCA</option>
                                        <option value="mandiri">MANDIRI</option>
                                        <option value="transfer">TRANSFER</option>
                                        <option value="debit">DEBIT</option>
                                        <option value="qris">QRIS</option>
                                        <option value="cash">CASH</option>
                                    </x-ui.select>
                                </div>
                                @error('metode_pembayaran')
                                    <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-8 w-full max-w-full px-3 shrink-0 md:flex-0">
                                <x-ui.button type="submit" title="Submit" color="primary" wireLoading
                                    formAction="submit" />
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
