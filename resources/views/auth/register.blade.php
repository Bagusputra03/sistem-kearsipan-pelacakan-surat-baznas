<x-guest-layout>
    <div class="bg-white/10 backdrop-blur-lg rounded-xl p-8 shadow-2xl border border-white/20 max-h-[90vh] overflow-y-auto text-white">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-white mb-2">Daftar Akun Baru</h2>
            <p class="text-green-100">Sistem Informasi Bantuan Zakat BAZNAS</p>
        </div>

        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label for="userType" class="block text-white font-medium mb-2">Tipe Pengguna</label>
                <select id="userType" name="userType" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
                    <option value="mustahik" class="text-gray-800" {{ old('userType') == 'mustahik' ? 'selected' : '' }}>Mustahik (Penerima Zakat)</option>
                    <option value="officer" class="text-gray-800" {{ old('userType') == 'officer' ? 'selected' : '' }}>Petugas BAZNAS</option>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-white font-medium mb-2">Nama Lengkap</label>
                    <input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                           class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent"
                           placeholder="Masukkan nama lengkap" />
                </div>

                <div>
                    <label for="nik" class="block text-white font-medium mb-2">NIK</label>
                    <input id="nik" type="text" name="nik" :value="old('nik')" required maxlength="16"
                           class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent"
                           placeholder="Nomor Induk Kependudukan" />
                </div>
            </div>

            <div>
                <label for="email" class="block text-white font-medium mb-2">Email</label>
                <input id="email" type="email" name="email" :value="old('email')" required
                       class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent"
                       placeholder="Masukkan email" />
            </div>

            <div>
                <label for="phone" class="block text-white font-medium mb-2">Nomor Telepon</label>
                <input id="phone" type="tel" name="phone" :value="old('phone')" required
                       class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent"
                       placeholder="Masukkan nomor telepon" />
            </div>

            <div>
                <label for="address" class="block text-white font-medium mb-2">Alamat Lengkap</label>
                <textarea id="address" name="address" rows="3" required
                          class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent"
                          placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-white font-medium mb-2">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                           class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent"
                           placeholder="Minimal 8 karakter" />
                </div>

                <div>
                    <label for="password_confirmation" class="block text-white font-medium mb-2">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                           class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent"
                           placeholder="Ulangi password" />
                </div>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-green-900 font-bold py-3 px-4 rounded-lg transition-all duration-300 transform hover:scale-105">
                Daftar
            </button>
        </form>

        <div class="text-center mt-6">
            <p class="text-green-100">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-yellow-400 hover:text-yellow-300 font-medium underline">
                    Masuk di sini
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>