@props(['variant' => 'primary', 'size' => 'md', 'href' => null])

@php
$base = 'inline-flex items-center justify-center font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-accent/50';
$variants = [
    'primary' => 'bg-primary text-white hover:bg-gray-700',
    'accent' => 'bg-accent text-white hover:bg-amber-600',
    'secondary' => 'bg-white text-primary border border-border hover:bg-gray-50',
    'danger' => 'bg-red-600 text-white hover:bg-red-700',
    'ghost' => 'text-secondary hover:text-primary hover:bg-gray-50',
];
$sizes = [
    'sm' => 'px-3 py-1.5 text-xs gap-1.5',
    'md' => 'px-4 py-2 text-sm gap-2',
    'lg' => 'px-5 py-2.5 text-sm gap-2',
];
$classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
