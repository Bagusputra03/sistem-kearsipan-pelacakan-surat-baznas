<div class="bg-green-900/95 backdrop-blur-lg rounded-xl p-6 md:p-8 border border-white/20 shadow-lg max-w-lg w-full max-h-[90vh] overflow-y-auto">
    <form wire:submit.prevent="save" class="space-y-6">
        <h3 class="text-xl font-bold text-white mb-4">
            {{ $isEditMode ? 'Edit Arsip Internal' : 'Upload Arsip Internal Baru' }}
        </h3>

        <div>
            <label for="title" class="block font-medium text-sm text-green-100">Judul Dokumen</label>
            <input wire:model="title" id="title" type="text" placeholder="Cth: Daftar Hadir Rapat November" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent mt-1">
            @error('title') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="document_date" class="block font-medium text-sm text-green-100">Tanggal Dokumen</label>
            <input wire:model="document_date" id="document_date" type="date" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent mt-1">
            @error('document_date') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="description" class="block font-medium text-sm text-green-100">Keterangan (Opsional)</label>
            <textarea wire:model="description" id="description" rows="3" placeholder="Keterangan singkat mengenai dokumen..." class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent mt-1"></textarea>
            @error('description') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="file" class="block font-medium text-sm text-green-100">Upload File (PDF/JPG/Word/Excel, Max 5MB)</label>
            @if($isEditMode)
                <p class="text-xs text-yellow-400 mb-1">Kosongkan jika tidak ingin mengubah file yang ada.</p>
            @endif
            <input wire:model.live="file" id="file" type="file" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent mt-1">
            <div wire:loading wire:target="file" class="text-yellow-400 text-sm mt-2">Mengunggah...</div>
            @error('file') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end gap-4 pt-4">
            <button wire:click="$dispatch('closeModal')" type="button" class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white font-bold rounded-lg border border-white/30 text-xs uppercase">
                Batal
            </button>

            <button type="submit" class="inline-flex items-center px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-green-900 font-bold rounded-lg text-xs uppercase">
                <span wire:loading.remove>Simpan Arsip</span>
                <span wire:loading>Menyimpan...</span>
            </button>
        </div>
    </form>
</div>