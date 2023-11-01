<div @if ($inline ?? '') class="flex items-center gap-x-3" @endif>
    <label for="{{ $name ?? '' }}"
        class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">{{ $label ?? '' }}</label>
    <input type="text" name="{{ $name ?? '' }}" id="{{ $id ?? '' }}" x-data="{{ $attributes->get('x-data') }}"
        x-on:input="{{ $attributes->get('x-on:input') }}" wire:model.defer="{{ $attributes->get('wire:model') }}"
        value="{{ $attributes->get('value') }}"
        class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
</div>
