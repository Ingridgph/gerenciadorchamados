@props(['title', 'value', 'color' => 'blue', 'icon' => null, 'subtitle' => null])

@php
    $colorClasses = [
        'blue' => 'stat-card-blue',
        'yellow' => 'stat-card-yellow',
        'green' => 'stat-card-green',
        'purple' => 'stat-card-purple',
    ];

    $iconBg = [
        'blue' => 'bg-blue-50 text-blue-600',
        'yellow' => 'bg-amber-50 text-amber-600',
        'green' => 'bg-emerald-50 text-emerald-600',
        'purple' => 'bg-violet-50 text-violet-600',
    ];
@endphp

<div class="stat-card {{ $colorClasses[$color] ?? $colorClasses['blue'] }} card-hover">
    <div class="flex items-start justify-between">
        <div class="space-y-2">
            <p class="text-sm font-medium text-gray-500">{{ $title }}</p>
            <p class="text-3xl font-bold tracking-tight text-gray-900">{{ $value }}</p>
            @if($subtitle)
                <p class="text-xs text-gray-400">{{ $subtitle }}</p>
            @endif
        </div>
        @if($icon)
            <div class="flex h-11 w-11 items-center justify-center rounded-xl {{ $iconBg[$color] ?? $iconBg['blue'] }}">
                {!! $icon !!}
            </div>
        @endif
    </div>
</div>
