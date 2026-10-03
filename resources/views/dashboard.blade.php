<x-app-layout>
    <x-slot name="header">
        <h2 class="p-6 glass-morphism rounded-lg text-white">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(auth()->user()->userType === 'mustahik')
                @livewire('mustahik-dashboard')
            @else
                @livewire('officer-dashboard')
            @endif
        </div>
    </div>
</x-app-layout>