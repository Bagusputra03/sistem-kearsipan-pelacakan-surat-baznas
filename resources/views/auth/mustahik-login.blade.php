<x-guest-layout>
    <div class="bg-white/10 backdrop-blur-lg rounded-xl p-8 shadow-2xl border border-white/20 text-white">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-white mb-2">Login Mustahik</h2>
            <p class="text-green-100">Lihat Riwayat Permohonan Anda</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('mustahik.login.store') }}" class="space-y-6">
            @csrf
            
            <div>
                <label for="nik" class="block text-white font-medium mb-2">NIK (Nomor Induk Kependudukan)</label>
                <input id="nik" type="text" name="nik" :value="old('nik')" required autofocus
                       class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent"
                       placeholder="Masukkan 16 digit NIK Anda" />
            </div>

            <div>
                <label for="password" class="block text-white font-medium mb-2">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent"
                       placeholder="Masukkan password Anda" />
            </div>
            
            <button type="submit" class="w-full bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-green-900 font-bold py-3 px-4 rounded-lg transition-all duration-300 transform hover:scale-105">
                Masuk
            </button>
        </form>

         <div class="text-center mt-6">
            <p class="text-green-100">
                Belum punya akun? Akun dibuatkan oleh petugas saat Anda mengajukan surat permohonan di kantor BAZNAS.
            </p>
        </div>
        <div class="text-center mt-6 border-t border-white/20 pt-4">
            <a href="{{ url('/') }}" class="text-yellow-400 hover:text-yellow-300 font-medium underline">
                &larr; Kembali ke Beranda
            </a>
        </div>
    </div>
</x-guest-layout>