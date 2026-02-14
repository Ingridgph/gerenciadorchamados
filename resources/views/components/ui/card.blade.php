<div {{ $attributes->merge(['class' => 'rounded-lg border bg-card text-card-foreground shadow-sm']) }}>
    <div class="p-6">
        {{ $slot }}
    </div>
</div>
