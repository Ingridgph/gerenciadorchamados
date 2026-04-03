@props(['variant' => 'default'])

@php
    $variants = [
        'default' => 'bg-gray-100 text-gray-700 ring-gray-200',
        'secondary' => 'bg-gray-100 text-gray-600 ring-gray-200',
        'destructive' => 'bg-red-50 text-red-700 ring-red-200',
        'outline' => 'bg-transparent text-gray-700 ring-gray-300',
        'success' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'warning' => 'bg-amber-50 text-amber-700 ring-amber-200',
        'info' => 'bg-blue-50 text-blue-700 ring-blue-200',
        'purple' => 'bg-violet-50 text-violet-700 ring-violet-200',
    ];

    $classes = "inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset transition-colors " . ($variants[$variant] ?? $variants['default']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
