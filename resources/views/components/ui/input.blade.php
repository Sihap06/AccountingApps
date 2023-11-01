<div class="relative" data-te-input-wrapper-init wire:ignore.self>
    <input type="{{ $type ?? 'text' }}"
        class="peer block min-h-[auto] w-full rounded-lg border-0 bg-transparent px-3 py-2 leading-[2.15] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:peer-focus:text-primary [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0 text-sm"
        id="{{ $id ?? '' }}" name="{{ $name ?? '' }}" value="{{ $value ?? '' }}"
        wire:model.defer="{{ $attributes->get('wire:model') }}" x-data="{{ $attributes->get('x-data') }}"
        x-on:input="{{ $attributes->get('x-on:input') }}" />
    <label for="{{ $id ?? '' }}"
        class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[2.15] text-neutral-500 transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-primary peer-data-[te-input-state-active]:-translate-y-[0.9rem] peer-data-[te-input-state-active]:scale-[0.8] motion-reduce:transition-none dark:text-neutral-200 dark:peer-focus:text-primary">
        {{ $label ?? '' }}
    </label>
</div>
