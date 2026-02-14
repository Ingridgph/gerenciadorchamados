@props(['disabled' => false, 'label' => null, 'name', 'id' => null, 'options' => [], 'value' => null])

@php
    $id = $id ?? $name;
@endphp

<div class="grid w-full items-center gap-1.5 mb-4">
    @if($label)
        <label for="{{ $id }}" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-gray-700 dark:text-gray-300">
            {{ $label }}
        </label>
    @endif
    <select
        id="{{ $id }}" 
        name="{{ $name }}" 
        {{ $disabled ? 'disabled' : '' }} 
        {!! $attributes->merge(['class' => 'flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50']) !!}
    >
        @foreach($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" {{ (string)$optionValue === (string)old($name, $value) ? 'selected' : '' }}>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
    @error($name)
        <p class="text-[0.8rem] font-medium text-destructive mt-1">{{ $message }}</p>
    @enderror
</div>
