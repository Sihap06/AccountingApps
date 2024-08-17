<div
    class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border h-full w-full p-4 justify-center items-center">

    <form wire:submit.prevent="handleExport" class="w-full md:w-1/2">


        <div class="mb-4">
            <div class="flex gap-x-2 mt-4">
                <div class="w-full md:w-4/12">
                    <div class="flex items-center">
                        <input wire:model.defer='selectItems' id="transaction" type="checkbox" value="transaction"
                            @if (in_array('transaction', $selectItems)) checked @endif
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="transaction"
                            class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Transaction</label>
                    </div>
                </div>
                <div class="w-full md:w-4/12">
                    <div class="flex items-center">
                        <input wire:model.defer='selectItems' id="expenditure" type="checkbox" value="expenditure"
                            @if (in_array('expenditure', $selectItems)) checked @endif
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="expenditure"
                            class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Expenditure</label>
                    </div>
                </div>
                <div class="w-full md:w-4/12">
                    <div class="flex items-center">
                        <input wire:model.defer='selectItems' id="income" type="checkbox" value="income"
                            @if (in_array('income', $selectItems)) checked @endif
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="income"
                            class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Income</label>
                    </div>
                </div>
                <div class="w-full md:w-4/12">
                    <div class="flex items-center">
                        <input wire:model.defer='selectItems' id="technician" type="checkbox" value="technician"
                            @if (in_array('technician', $selectItems)) checked @endif
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="technician"
                            class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Technician</label>
                    </div>
                </div>
                <div class="w-full md:w-4/12">
                    <div class="flex items-center">
                        <input wire:model.defer='selectItems' id="netto" type="checkbox" value="netto"
                            @if (in_array('netto', $selectItems)) checked @endif
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="netto"
                            class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Netto</label>
                    </div>
                </div>
            </div>
            @if ($errors->has('selectItems'))
                <span class="text-red-500 text-xs">{{ $errors->first('selectItems') }}</span>
            @endif
        </div>


        <div class="flex gap-x-2">
            <div class="w-full md:w-4/12">
                <div class="flex items-center">
                    <select wire:model="month"
                        class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                        <option value="" selected>Pilih Bulan</option>
                        <option value="01">Januari</option>
                        <option value="02">Februari</option>
                        <option value="03">Maret</option>
                        <option value="04">April</option>
                        <option value="05">Mei</option>
                        <option value="06">Juni</option>
                        <option value="07">Juli</option>
                        <option value="08">Agustus</option>
                        <option value="09">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                </div>
                @if ($errors->has('month'))
                    <span class="text-red-500 text-xs">{{ $errors->first('month') }}</span>
                @endif
            </div>

            <div class="w-full md:w-4/12">
                <div class="flex items-center">
                    <select wire:model="year"
                        class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                        <option value="" selected>Pilih Tahun</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                        <option value="2028">2028</option>
                    </select>
                </div>
                @if ($errors->has('year'))
                    <span class="text-red-500 text-xs">{{ $errors->first('year') }}</span>
                @endif
            </div>
        </div>

        <div class="w-full md:w-4/12 mt-2">
            <button type="submit" class="bg-primary rounded p-2 w-full">
                <div wire:loading wire:target='handleExport'>
                    <div class="text-white inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                        role="status">
                        <span
                            class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                    </div>
                </div>
                <span class="text-white">Export</span>
            </button>
        </div>
    </form>

    {{-- <form wire:submit.prevent="handleExport">
        <div class="flex gap-x-4 items-start w-full mt-4">
            <div class="w-full md:w-4/12">
                <div class="flex items-center">
                    <select wire:model="month"
                        class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                        <option value="" selected>Pilih Bulan</option>
                        <option value="01">Januari</option>
                        <option value="02">Februari</option>
                        <option value="03">Maret</option>
                        <option value="04">April</option>
                        <option value="05">Mei</option>
                        <option value="06">Juni</option>
                        <option value="07">Juli</option>
                        <option value="08">Agustus</option>
                        <option value="09">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                </div>
                @if ($errors->has('month'))
                    <span class="text-red-500 text-xs">{{ $errors->first('month') }}</span>
                @endif
            </div>

            <div class="w-full md:w-4/12">
                <div class="flex items-center">
                    <select wire:model="year"
                        class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                        <option value="" selected>Pilih Tahun</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                        <option value="2028">2028</option>
                    </select>
                </div>
                @if ($errors->has('year'))
                    <span class="text-red-500 text-xs">{{ $errors->first('year') }}</span>
                @endif
            </div>



            <div class="flex w-full md:w-4/12 items-center">
                <button type="submit" class="bg-primary rounded p-2">
                    <div wire:loading wire:target='handleExport'>
                        <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                            role="status">
                            <span
                                class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                        </div>
                    </div>
                    <span class="text-white">Export</span>
                </button>
            </div>

        </div>
    </form> --}}

</div>
