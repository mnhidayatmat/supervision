@props(['title' => null, 'padding' => true])

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg border border-border']) }}>
    @if($title)
        <div class="px-5 py-3 border-b border-border">
            <h3 class="text-sm font-semibold text-primary">{{ $title }}</h3>
        </div>
    @endif
    <div class="{{ $padding ? 'p-5' : '' }}">
        {{ $slot }}
    </div>
</div>
