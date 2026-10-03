@props(['href' => null])

@php
    $classes = 'inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-lg text-white font-bold rounded-lg border border-white/30 transition-all duration-300 uppercase tracking-widest text-xs';
@endphp

@if ($href)
    <a {{ $attributes->merge(['href' => $href, 'class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => 'button', 'class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif