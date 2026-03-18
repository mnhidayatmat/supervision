@props(['label', 'value', 'color' => 'accent'])

<div class="bg-white rounded-lg border border-border p-4">
    <p class="text-xs font-medium text-secondary uppercase tracking-wide">{{ $label }}</p>
    <p class="mt-1 text-2xl font-semibold text-primary">{{ $value }}</p>
</div>
