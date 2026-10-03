<div class="bg-white/10 backdrop-blur-lg rounded-xl p-8 shadow-2xl border border-white/20 max-h-[90vh] overflow-y-auto">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-white">Ajukan Permohonan Bantuan</h2>
        <button wire:click="$dispatch('closeModal')" class="text-white hover:text-red-300 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>

    <form wire:submit.prevent="submit" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-white font-medium mb-2">Jenis Bantuan</label>
                <select wire:model.defer="requestType" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-yellow-400">
                    <option value="" class="text-gray-800">Pilih jenis bantuan</option>
                    <option value="kesehatan" class="text-gray-800">Bantuan Kesehatan</option>
                    <option value="pendidikan" class="text-gray-800">Bantuan Pendidikan</option>
                    <option value="konsumtif" class="text-gray-800">Bantuan Konsumtif</option>
                    <option value="produktif" class="text-gray-800">Bantuan Produktif/Usaha</option>
                    <option value="darurat" class="text-gray-800">Bantuan Darurat</option>
                </select>
                @error('requestType') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-white font-medium mb-2">Jumlah Bantuan (Rp)</label>
                <input type="number" wire:model.defer="amount" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400" placeholder="Masukkan jumlah bantuan">
                @error('amount') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label class="block text-white font-medium mb-2">Tingkat Urgensi</label>
            <select wire:model.defer="urgency" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-yellow-400">
                <option value="normal" class="text-gray-800">Normal</option>
                <option value="urgent" class="text-gray-800">Mendesak</option>
                <option value="critical" class="text-gray-800">Sangat Mendesak</option>
            </select>
            @error('urgency') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-white font-medium mb-2">Anggota Keluarga</label>
                <input type="number" wire:model.defer="familyMembers" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400" placeholder="Jumlah">
                @error('familyMembers') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-white font-medium mb-2">Penghasilan/Bulan</label>
                <input type="number" wire:model.defer="monthlyIncome" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400" placeholder="Penghasilan">
                @error('monthlyIncome') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-white font-medium mb-2">Status Pekerjaan</label>
                <select wire:model.defer="jobStatus" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-yellow-400">
                    <option value="" class="text-gray-800">Pilih status</option>
                    <option value="employed" class="text-gray-800">Bekerja</option>
                    <option value="unemployed" class="text-gray-800">Tidak Bekerja</option>
                    <option value="freelance" class="text-gray-800">Pekerja Lepas</option>
                    <option value="retired" class="text-gray-800">Pensiunan</option>
                </select>
                @error('jobStatus') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label class="block text-white font-medium mb-2">Alasan Permohonan</label>
            <textarea wire:model.defer="reason" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400" placeholder="Jelaskan alasan mengapa membutuhkan bantuan" rows="3"></textarea>
            @error('reason') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-white font-medium mb-2">Keterangan Tambahan</label>
            <textarea wire:model.defer="description" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400" placeholder="Informasi tambahan yang diperlukan" rows="3"></textarea>
            @error('description') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex gap-4">
            <button type="button" wire:click="$dispatch('closeModal')" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded-lg transition-all duration-300">
                Batal
            </button>
            <button type="submit" class="flex-1 bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-green-900 font-bold py-3 px-4 rounded-lg transition-all duration-300">
                Kirim Permohonan
            </button>
        </div>
    </form>
</div>