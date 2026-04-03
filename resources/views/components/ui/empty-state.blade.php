@props(['title' => 'Nenhum item encontrado', 'description' => null, 'icon' => null, 'actionLabel' => null, 'actionUrl' => null])

<div class="empty-state animate-in">
    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100 mb-4">
        @if($icon)
            {!! $icon !!}
        @else
            <svg class="h-7 w-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
        @endif
    </div>
    <h3 class="text-sm font-semibold text-gray-900">{{ $title }}</h3>
    @if($description)
        <p class="mt-1 text-sm text-gray-500 max-w-sm">{{ $description }}</p>
    @endif
    @if($actionLabel && $actionUrl)
        <div class="mt-5">
            <x-ui.button :href="$actionUrl" size="sm">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                {{ $actionLabel }}
            </x-ui.button>
        </div>
    @endif
</div>
