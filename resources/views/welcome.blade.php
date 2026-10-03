<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('images/logo-icon.png') }}" type="image/png">
    <title>BAZNAS KABUPATEN KUDUS - Sistem Informasi Kearsipan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="min-h-screen bg-gradient-to-br from-green-900 via-green-800 to-emerald-900 relative overflow-hidden text-white">
        
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-yellow-400/20 to-transparent"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 rounded-full bg-yellow-400/10 blur-3xl"></div>
            <div class="absolute top-1/4 left-1/4 w-64 h-64 rounded-full bg-green-400/10 blur-2xl"></div>
        </div>

        <div class="relative z-10 min-h-screen flex flex-col">
            <header class="p-6">
                <div class="max-w-6xl mx-auto flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('images/logo-icon.png') }}" alt="Logo BAZNAS" class="w-12 h-12 object-contain">
                        <div>
                            <h1 class="text-2xl font-bold text-white">BAZNAS KABUPATEN KUDUS</h1>
                            <p class="text-green-100 text-sm">Sistem Informasi Kearsipan & Pelacakan</p>
                        </div>
                    </div>
                    
                    @if (Route::has('login'))
                        <div class="space-x-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="bg-yellow-400 hover:bg-yellow-500 text-green-900 font-bold py-2 px-6 rounded-lg transition-all duration-300 transform hover:scale-105">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="bg-yellow-400 hover:bg-yellow-500 text-green-900 font-bold py-2 px-6 rounded-lg transition-all duration-300 transform hover:scale-105">
                                    Login Petugas
                                </a>
                            @endauth
                        </div>
                    @endif
                </div>
            </header>

            <main class="flex-1 pt-8 sm:pt-12">
                <div class="max-w-6xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                        
                        <div class="space-y-8">
                            <div class="space-y-4">
                                <h2 class="text-5xl lg:text-6xl font-bold text-white leading-tight">
                                    Sistem Informasi <span class="text-yellow-400">Arsip</span> & Pelacakan Surat
                                </h2>
                                <p class="text-xl text-green-100 leading-relaxed">
                                    Memudahkan pengelolaan surat masuk dan memberikan transparansi status permohonan bantuan bagi Mustahik melalui pelacakan digital.
                                </p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-8">
                                <div class="flex items-start space-x-3">
                                    <div class="w-8 h-8 bg-yellow-400/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-white">Arsip Digital</h3>
                                        <p class="text-green-200 text-sm">Semua surat masuk terdata dan tersimpan aman</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <div class="w-8 h-8 bg-yellow-400/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-white">Pelacakan Real-time</h3>
                                        <p class="text-green-200 text-sm">Mustahik dapat memantau status surat/permohonan</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <div class="w-8 h-8 bg-yellow-400/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-white">Proses Terpusat</h3>
                                        <p class="text-green-200 text-sm">Alur disposisi dan persetujuan yang jelas</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <div class="w-8 h-8 bg-yellow-400/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path></svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-white">Akuntabel</h3>
                                        <p class="text-green-200 text-sm">Memudahkan pelaporan dan audit</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-8">
                            <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 border border-white/20">
                                <div class="space-y-6">
                                    <div class="text-center">
                                        <h3 class="text-2xl font-bold text-white mb-2">Lacak Status Surat</h3>
                                        <p class="text-green-100">Masukkan Kode Tracking dari Tanda Terima Anda</p>
                                    </div>

                                    @if(session('error'))
                                        <div class="bg-red-500/20 border border-red-400 text-red-100 px-4 py-3 rounded-lg">
                                            {{ session('error') }}
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('tracking.submit') }}" class="space-y-4">
                                        @csrf
                                        <div>
                                            <label for="tracking_code" class="sr-only">Kode Tracking</label>
                                            <input type="text" name="tracking_code" id="tracking_code"
                                                   class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent"
                                                   placeholder="Contoh: BZN-HN8ATS" required>
                                        </div>
                                        <button type="submit" class="w-full bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-green-900 font-bold py-3 px-4 rounded-lg transition-all duration-300 transform hover:scale-105">
                                            Lacak
                                        </button>
                                    </form>

                                    <div class="flex items-center justify-center space-x-2">
                                        <span class="h-px bg-white/20 w-full"></span>
                                        <span class="text-green-100 text-sm">ATAU</span>
                                        <span class="h-px bg-white/20 w-full"></span>
                                    </div>

                                    <a href="{{ route('mustahik.login') }}" class="w-full bg-white/20 hover:bg-white/30 backdrop-blur-lg text-white font-bold py-3 px-6 rounded-lg border border-white/30 transition-all duration-300 flex items-center justify-center space-x-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        <span>Masuk sebagai Mustahik (Lihat Riwayat)</span>
                                    </a>
                                </div>
                            </div>

                            <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 border border-white/20">
                                <div class="space-y-4 text-center">
                                    <h3 class="text-2xl font-bold text-white mb-2">Akses Petugas</h3>
                                    <p class="text-green-100">Hanya untuk Petugas (Amil) BAZNAS</p>
                                    <a href="{{ route('login') }}" class="w-full bg-white/20 hover:bg-white/30 backdrop-blur-lg text-white font-bold py-3 px-6 rounded-lg border border-white/30 transition-all duration-300 flex items-center justify-center space-x-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        <span>Masuk Sebagai Petugas</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <footer class="p-6 mt-16 sm:mt-24"> <div class="max-w-6xl mx-auto text-center">
                    <p class="text-green-200">
                        BAZNAS - Badan Amil Zakat Nasional Kabupaten Kudus
                    </p>
                </div>
            </footer>
        </div>
    </div>
</body>
</html>