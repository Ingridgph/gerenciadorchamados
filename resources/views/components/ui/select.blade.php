@props(['disabled' => false, 'label' => null, 'name', 'id' => null, 'options' => [], 'value' => null, 'placeholder' => null])

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
        <select
            id="{{ $id }}"
            name="{{ $name }}"
            {{ $disabled ? 'disabled' : '' }}
            {!! $attributes->merge(['class' => 'block w-full appearance-none rounded-xl border border-gray-200 bg-white px-4 py-2.5 pr-10 text-sm text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:opacity-60 transition-all duration-150']) !!}
        >
            @if($placeholder)
                <option value="">{{ $placeholder }}</option>
            @endif
            @foreach($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" {{ (string)$optionValue === (string)old($name, $value) ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </div>
    @error($name)
        <p class="flex items-center gap-1 text-xs font-medium text-red-600 mt-1">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            {{ $message }}
        </p>
    @enderror
</div>
