<div class="bg-green-900/95 rounded-xl p-6 md:p-8 border border-white/20 shadow-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
    <form wire:submit.prevent="save" class="space-y-6">
        <h2 class="text-2xl font-bold text-white mb-6">
            {{ $isEditMode ? 'Edit Surat Masuk' : 'Input Surat Masuk Baru' }}
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="sender_name" class="block font-medium text-sm text-green-100">Nama Pengirim / Asal Surat</label>
                <input wire:model="sender_name" id="sender_name" type="text" placeholder="Isi Nama Pengirim" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent mt-1">
                @error('sender_name') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="letter_number" class="block font-medium text-sm text-green-100">Nomor Surat (Jika ada)</label>
                <input wire:model="letter_number" id="letter_number" type="text" placeholder="Cth: 123/IX/2025" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent mt-1">
                @error('letter_number') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="letter_date" class="block font-medium text-sm text-green-100">Tanggal Surat</label>
                <input wire:model="letter_date" id="letter_date" type="date" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent mt-1">
                @error('letter_date') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
            </div>
             <div>
                <label for="category" class="block font-medium text-sm text-green-100">Kategori Surat</label>
                <select wire:model.live="category" id="category" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent mt-1" {{ $isEditMode ? 'disabled' : '' }}>
                    <option value="Permohonan Bantuan" class="text-gray-800">Permohonan Bantuan</option>
                    <option value="Undangan" class="text-gray-800">Undangan</option>
                    <option value="Kerjasama" class="text-gray-800">Kerjasama</option>
                    <option value="Internal" class="text-gray-800">Internal</option>
                    <option value="Lainnya" class="text-gray-800">Lainnya</option>
                </select>
                @if($isEditMode)
                    <p class="text-xs text-yellow-400 mt-1">Kategori tidak dapat diubah saat edit.</p>
                @endif
                @error('category') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label for="subject" class="block font-medium text-sm text-green-100">Perihal</label>
            <textarea wire:model="subject" id="subject" rows="2" placeholder="Cth: Permohonan bantuan biaya pendidikan" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent mt-1"></textarea>
            @error('subject') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="file_scan" class="block font-medium text-sm text-green-100">Upload Scan Surat (PDF/JPG/PNG, Max 2MB)</label>
            @if($isEditMode)
                <p class="text-xs text-yellow-400 mb-1">Kosongkan jika tidak ingin mengubah file scan.</p>
            @endif
            <input wire:model.live="file_scan" id="file_scan" type="file" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent mt-1">
            <div wire:loading wire:target="file_scan" class="text-yellow-400 text-sm mt-2">Mengunggah file...</div>
            @error('file_scan') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
        </div>

        
        @if ($category === 'Permohonan Bantuan')
            <div class="space-y-6 border-t border-white/20 pt-6">
                
                <h3 class="text-xl font-semibold text-white">Detail Permohonan Bantuan</h3>
                
                <div>
                    <label for="nik_mustahik" class="block font-medium text-sm text-green-100">Cari NIK Mustahik</label>
                    <div class="flex gap-2 mt-1">
                        <input wire:model="nik_mustahik" id="nik_mustahik" type="text" placeholder="Masukkan 16 digit NIK" class="flex-1 px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent" {{ $isEditMode ? 'disabled' : '' }}>
                        <button wire:click="searchMustahik" type="button" class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white font-bold rounded-lg border border-white/30 text-xs" {{ $isEditMode ? 'disabled' : '' }}>Cari</button>
                    </div>
                    @if($isEditMode)
                        <p class="text-xs text-yellow-400 mt-1">NIK Mustahik tidak dapat diubah saat edit.</p>
                    @endif
                    @error('nik_mustahik') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div wire:loading wire:target="searchMustahik">
                    <p class="text-yellow-400">Mencari...</p>
                </div>
                
                @if ($foundMustahik)
                    <div class="bg-green-500/20 border border-green-400 text-green-100 px-4 py-3 rounded-lg">
                        Mustahik ditemukan: <strong>{{ $foundMustahik->name }}</strong> (NIK: {{ $foundMustahik->nik }})
                    </div>
                @elseif ($foundMustahik === false)
                    <div class="space-y-4 bg-white/10 p-4 rounded-lg">
                        <h4 class="text-white font-semibold">NIK tidak ditemukan. Silakan isi data Mustahik baru:</h4>
                        <div>
                            <label for="mustahik_name" class="block font-medium text-sm text-green-100">Nama Mustahik</label>
                            <input wire:model="mustahik_name" id="mustahik_name" type="text" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white mt-1">
                            @error('mustahik_name') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="mustahik_phone" class="block font-medium text-sm text-green-100">No. Telepon</label>
                                <input wire:model="mustahik_phone" id="mustahik_phone" type="tel" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white mt-1">
                                @error('mustahik_phone') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="mustahik_address" class="block font-medium text-sm text-green-100">Alamat</label>
                                <input wire:model="mustahik_address" id="mustahik_address" type="text" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white mt-1">
                                @error('mustahik_address') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                @endif
                
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
                    <label class="block text-white font-medium mb-2">Tingkat Urgensi</label>
                    <select wire:model.defer="urgency" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-yellow-400">
                        <option value="normal" class="text-gray-800">Normal</option>
                        <option value="urgent" class="text-gray-800">Mendesak</option>
                        <option value="critical" class="text-gray-800">Sangat Mendesak</option>
                    </select>
                    @error('urgency') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
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

            </div>
        @endif
        
        <div class="flex items-center justify-end pt-4 gap-4">
            <button type="button" wire:click="$dispatch('closeModal')" class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-lg text-white font-bold rounded-lg border border-white/30 transition-all duration-300 uppercase tracking-widest text-xs">
                Batal
            </button>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-green-900 font-bold rounded-lg transition-all duration-300 transform hover:scale-105 uppercase tracking-widest text-xs">
                <span wire:loading.remove>Simpan Arsip Surat</span>
                <span wire:loading>Menyimpan...</span>
            </button>
        </div>
    </form>
</div>