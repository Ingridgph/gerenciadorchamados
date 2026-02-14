@props(['disabled' => false, 'label' => null, 'name', 'type' => 'text', 'value' => '', 'id' => null])

@php
    $id = $id ?? $name;
@endphp

<div class="grid w-full items-center gap-1.5 mb-4">
    @if($label)
        <label for="{{ $id }}" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-gray-700 dark:text-gray-300">
            {{ $label }}
        </label>
    @endif
    <input 
        type="{{ $type }}" 
        id="{{ $id }}" 
        name="{{ $name }}" 
        value="{{ old($name, $value) }}"
        {{ $disabled ? 'disabled' : '' }} 
        {!! $attributes->merge(['class' => 'flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50']) !!}
    >
    @error($name)
        <p class="text-[0.8rem] font-medium text-destructive mt-1">{{ $message }}</p>
    @enderror
</div>
