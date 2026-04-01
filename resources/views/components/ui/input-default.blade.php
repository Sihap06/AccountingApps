@props([
    'label' => '',
    'name' => '',
    'type' => 'text',
    'id' => null,
    'value' => '',
    'disabled' => false,
    'placeholder' => '',
    'required' => false,
    'error' => null,
    'inline' => false
])

<div @if ($inline) class="flex items-center gap-x-3" @endif>
    <label for="{{ $id ?? $name }}"
        class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">
        {{ $label }}
        @if($required)
            <span class="text-red-600">*</span>
        @endif
    </label>
    <input 
        type="{{ $type }}" 
        name="{{ $name }}" 
        id="{{ $id ?? $name }}" 
        placeholder="{{ $placeholder }}"
        @if($value && !$attributes->has('wire:model'))
            value="{{ $value }}"
        @endif
        @if($disabled)
            disabled
        @endif
        {{ $attributes->merge([
            'class' => 'focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none' . ($disabled ? ' bg-gray-100 cursor-not-allowed' : '')
        ]) }}>
    @if($error)
        <span class="text-red-500 text-xs mt-1 block">{{ $error }}</span>
    @endif
</div>
