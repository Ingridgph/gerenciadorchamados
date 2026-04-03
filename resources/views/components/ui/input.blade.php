@props(['disabled' => false, 'label' => null, 'name', 'type' => 'text', 'value' => '', 'id' => null, 'placeholder' => '', 'icon' => null])

@php
    $id = $id ?? $name;
@endphp

<div class="space-y-1.5 mb-5">
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700">
            {{ $label }}
        </label>
    @endif
    <div class="relative">
        @if($icon)
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                {!! $icon !!}
            </div>
        @endif
        <input
            type="{{ $type }}"
            id="{{ $id }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $disabled ? 'disabled' : '' }}
            {!! $attributes->merge(['class' => 'block w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:opacity-60 transition-all duration-150' . ($icon ? ' pl-10' : '')]) !!}
        >
    </div>
    @error($name)
        <p class="flex items-center gap-1 text-xs font-medium text-red-600 mt-1">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            {{ $message }}
        </p>
    @enderror
</div>
