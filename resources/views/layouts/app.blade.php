<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-t">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="icon" href="{{ asset('images/logo-icon.png') }}" type="image/png">
        <title>BAZNAS KABUPATEN KUDUS - Sistem Informasi Kearsipan</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css']) 
        @livewireStyles 
    </head>
    <body class="font-sans antialiased min-h-screen bg-gradient-to-br from-green-900 via-green-800 to-emerald-900">
        
        @include('layouts.navigation') 

        <div class="pt-16"> @isset($header)
                <header>
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                {{ $slot }}
            </main>
        </div>

        @vite(['resources/js/app.js']) @livewireScripts               @stack('scripts')
    </body>
</html>