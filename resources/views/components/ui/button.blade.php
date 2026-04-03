@props(['variant' => 'default', 'size' => 'default', 'disabled' => false, 'href' => null])

@php
    $baseClass = "inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-xl text-sm font-semibold ring-offset-background transition-all duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 active:scale-[0.98]";

    $variants = [
        'default' => 'bg-primary text-primary-foreground hover:bg-primary/90 shadow-sm',
        'destructive' => 'bg-destructive text-destructive-foreground hover:bg-destructive/90 shadow-sm',
        'outline' => 'border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 shadow-sm',
        'secondary' => 'bg-gray-100 text-gray-700 hover:bg-gray-200',
        'ghost' => 'hover:bg-gray-100 text-gray-700',
        'link' => 'text-primary underline-offset-4 hover:underline',
        'success' => 'bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm',
    ];

    $sizes = [
        'default' => 'h-10 px-5 py-2',
        'sm' => 'h-9 px-3.5 text-xs',
        'lg' => 'h-12 px-8 text-base',
        'icon' => 'h-10 w-10',
        'icon-sm' => 'h-8 w-8',
    ];

    $classes = $baseClass . ' ' . ($variants[$variant] ?? $variants['default']) . ' ' . ($sizes[$size] ?? $sizes['default']);
@endphp

@if($href)
    <a href="{{ $href }}" {!! $attributes->merge(['class' => $classes]) !!}>
        {{ $slot }}
    </a>
@else
    <button {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => $classes]) !!}>
        {{ $slot }}
    </button>
@endif
