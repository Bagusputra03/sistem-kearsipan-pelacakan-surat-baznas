@props(['errors'])

@if ($errors->any())
    <div {{ $attributes->merge(['class' => 'bg-red-500/20 border border-red-400 text-red-100 px-4 py-3 rounded-lg']) }}>
        <div class="font-medium text-red-300">
            {{ __('Whoops! Ada yang salah.') }}
        </div>

        <ul class="mt-3 list-disc list-inside text-sm text-red-200">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif