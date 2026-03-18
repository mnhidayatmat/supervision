@props(['name', 'title' => ''])

<div x-data="{ open: false }" x-on:open-modal-{{ $name }}.window="open = true" x-on:close-modal-{{ $name }}.window="open = false" x-on:keydown.escape.window="open = false">
    <div x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div x-show="open" class="fixed inset-0 bg-gray-900/50 transition-opacity" @click="open = false"></div>
            <div x-show="open" x-transition class="relative bg-white rounded-xl shadow-xl max-w-lg w-full mx-auto z-10">
                <div class="flex items-center justify-between px-5 py-3 border-b border-border">
                    <h3 class="text-sm font-semibold">{{ $title }}</h3>
                    <button @click="open = false" class="text-secondary hover:text-primary">&times;</button>
                </div>
                <div class="p-5">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</div>
