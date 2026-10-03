<x-guest-layout>
    <div class="bg-white/10 backdrop-blur-lg rounded-xl p-8 shadow-2xl border border-white/20 text-white">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-white mb-2">Login Petugas</h2>
            <p class="text-green-100">Sistem Informasi Kearsipan BAZNAS</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            
            <input type="hidden" name="userType" value="officer">

            <div>
                <label for="email" class="block text-white font-medium mb-2">Email Petugas</label>
                <input id="email" type="email" name="email" :value="old('email')" required autofocus
                       class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent"
                       placeholder="Masukkan email Anda" />
            </div>

            <div>
                <label for="password" class="block text-white font-medium mb-2">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent"
                       placeholder="Masukkan password Anda" />
            </div>
            
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-yellow-600 shadow-sm focus:ring-yellow-500" name="remember">
                    <span class="ml-2 text-sm text-green-100">{{ __('Ingat saya') }}</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-green-900 font-bold py-3 px-4 rounded-lg transition-all duration-300 transform hover:scale-105">
                Masuk
            </button>
        </form>

        <!-- <div class="text-center mt-6">
            <p class="text-green-100">
                Lupa password?
                <a href="{{ route('password.request') }}" class="text-yellow-400 hover:text-yellow-300 font-medium underline">
                    Reset di sini
                </a>
            </p>
        </div> -->
        <div class="text-center mt-6 border-t border-white/20 pt-4">
            <a href="{{ url('/') }}" class="text-yellow-400 hover:text-yellow-300 font-medium underline">
                &larr; Kembali ke Beranda
            </a>
        </div>
    </div>
</x-guest-layout>