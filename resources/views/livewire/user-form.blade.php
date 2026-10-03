<div class="bg-green-900/95 backdrop-blur-lg rounded-xl p-6 md:p-8 border border-white/20 shadow-lg max-h-[90vh] overflow-y-auto">
    <form wire:submit.prevent="save" class="space-y-6">
        <h2 class="text-2xl font-bold text-white mb-6">
            {{ $isEditMode ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}
        </h2>

        <div>
            <label for="userType" class="block font-medium text-sm text-green-100">Tipe Pengguna</label>
            <select wire:model.live="userType" id="userType" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent mt-1">
                <option value="" class="text-gray-800">Pilih Tipe</option>
                <option value="mustahik" class="text-gray-800">Mustahik</option>
                <option value="officer" class="text-gray-800">Petugas</option>
            </select>
            @error('userType') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
        </div>

        @if ($userType === 'officer')
        <div class="block mt-4">
            <label for="is_pimpinan" class="inline-flex items-center">
                <input wire:model="is_pimpinan" id="is_pimpinan" type="checkbox" class="rounded border-gray-300 text-yellow-600 shadow-sm focus:ring-yellow-500">
                <span class="ms-2 text-sm text-green-100">{{ __('Jadikan Pimpinan (Hak Akses Penuh)') }}</span>
            </label>
            @error('is_pimpinan') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block font-medium text-sm text-green-100">Nama Lengkap</label>
                <input wire:model="name" id="name" type="text" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent mt-1">
                @error('name') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="nik" class="block font-medium text-sm text-green-100">NIK</label>
                <input wire:model="nik" id="nik" type="text" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent mt-1">
                @error('nik') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
             <div>
                <label for="email" class="block font-medium text-sm text-green-100">Email</label>
                <input wire:model="email" id="email" type="email" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent mt-1">
                @error('email') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="phone" class="block font-medium text-sm text-green-100">Telepon</label>
                <input wire:model="phone" id="phone" type="tel" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent mt-1">
                @error('phone') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label for="address" class="block font-medium text-sm text-green-100">Alamat</label>
            <textarea wire:model="address" id="address" rows="3" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent mt-1"></textarea>
            @error('address') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
        </div>

        <p class="text-green-100 text-sm">{{ $isEditMode ? 'Kosongkan jika tidak ingin ganti password' : '' }}</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="password" class="block font-medium text-sm text-green-100">Password</label>
                <input wire:model="password" id="password" type="password" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent mt-1">
                @error('password') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="password_confirmation" class="block font-medium text-sm text-green-100">Konfirmasi Password</label>
                <input wire:model="password_confirmation" id="password_confirmation" type="password" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent mt-1">
            </div>
        </div>

        <div class="flex items-center justify-end pt-4 gap-4">
            <button type="button" wire:click="$dispatch('closeModal')" class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-lg text-white font-bold rounded-lg border border-white/30 transition-all duration-300 uppercase tracking-widest text-xs">
                Batal
            </button>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-green-900 font-bold rounded-lg transition-all duration-300 transform hover:scale-105 uppercase tracking-widest text-xs">
                Simpan
            </button>
        </div>
    </form>
</div>