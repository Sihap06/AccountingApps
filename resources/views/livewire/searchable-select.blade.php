<div class="relative w-full" x-data="{ open: false }" @close-dropdown.window="open = false" @click.away="open = false">
    <label class='text-sm ' for="{{ $name }}">{{ $label }}</label>
    <input type="hidden" wire:model='{{ $name }}' id='{{ $name }}'>
    <div @click="open = !open"
        class="relative cursor-pointer bg-white border border-gray-300 rounded-lg pl-3 pr-10 py-2 text-left focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
        <span class="text-sm">{{ $selected ? $selectedName : 'Pilih ' . $label }}</span>
        <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
            <svg wire:loading.remove class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="none"
                stroke="currentColor">
                <path d="M7 7l3-3m0 0l3 3m-3-3v12" />
            </svg>

            <div wire:loading>
                <div class="inline-block h-3 w-3 animate-spin rounded-full border-2 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                    role="status">
                    <span
                        class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                </div>
            </div>
        </span>
    </div>

    <div x-show="open" x-ref="dropdown"
        x-on:open-dropdown.window="
             dropdownHeight = $refs.dropdown.offsetHeight;
             spaceBelow = window.innerHeight - $el.getBoundingClientRect().bottom;
             if (dropdownHeight > spaceBelow) {
                 $el.style.bottom = $el.offsetHeight + 'px'; 
             } else {
                 $el.style.bottom = 'auto'; // Reset if enough space below
             }
         "
        class="absolute my-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm z-10">
        <input wire:model="search" type="text" placeholder="Search..."
            class="w-full px-3 py-2 text-sm border-b border-gray-200 focus:outline-none focus:ring-0 sticky top-0 z-20">
        <div wire:click="selectOption('')"
            class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-blue-600 hover:text-white">
            <span class="text-sm">Pilih Opsi</span>
        </div>

        @foreach ($filteredOptions as $option)
            <div wire:click="selectOption('{{ $option['value'] }}')"
                class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-blue-600 hover:text-white">
                <span class="text-sm">{{ $option['label'] }}</span>
            </div>
        @endforeach
    </div>
</div>
