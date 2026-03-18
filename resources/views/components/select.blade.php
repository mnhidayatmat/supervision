@props(['label' => null, 'name', 'required' => false, 'options' => [], 'selected' => null])

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-primary mb-1">
            {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif
    <select
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'w-full rounded-lg border border-border bg-white px-3 py-2 text-sm text-primary focus:border-accent focus:ring-1 focus:ring-accent/30 outline-none transition-colors']) }}
    >
        <option value="">Select...</option>
        @foreach($options as $value => $label)
            <option value="{{ $value }}" {{ old($name, $selected) == $value ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
        {{ $slot }}
    </select>
    @error($name)
        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
    @enderror
</div>
