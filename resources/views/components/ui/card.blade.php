@props(['padding' => true])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-gray-100 bg-white shadow-card transition-all duration-200']) }}>
    @if($padding)
        <div class="p-6">
            {{ $slot }}
        </div>
    @else
        {{ $slot }}
    @endif
</div>
