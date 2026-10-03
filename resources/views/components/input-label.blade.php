@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-green-100']) }}>
    {{ $value ?? $slot }}
</label>