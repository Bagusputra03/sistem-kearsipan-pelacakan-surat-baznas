<div class="bg-green-900/95 backdrop-blur-lg rounded-xl p-6 md:p-8 border border-white/20 shadow-lg max-w-lg w-full max-h-[90vh] overflow-y-auto">
    <form wire:submit.prevent="saveFollowUp" class="space-y-6">
        <h3 class="text-xl font-bold text-white mb-4">Kirim Laporan Tindak Lanjut</h3>

        <div class="bg-white/10 rounded-lg p-4">
            <p class="text-sm text-green-200">Surat:</p>
            <p class="font-semibold text-white">{{ $disposition->incomingLetter->subject }}</p>
            <p class="text-sm text-green-200 mt-2">Instruksi dari Pimpinan:</p>
            <p class="font-semibold text-yellow-400">"{{ $disposition->notes }}"</p>
        </div>

        <div>
            <label for="follow_up_notes" class="block font-medium text-sm text-green-100">Laporan Tindak Lanjut</label>
            <textarea wire:model="follow_up_notes" id="follow_up_notes" rows="5" placeholder="Tuliskan hasil survei atau tindak lanjut Anda di sini..." class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent mt-1"></textarea>
            @error('follow_up_notes') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="follow_up_attachment" class="block font-medium text-sm text-green-100">Upload Bukti (Opsional - Foto/PDF, Max 2MB)</label>
            <input wire:model.live="follow_up_attachment" id="follow_up_attachment" type="file" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent mt-1">
            <div wire:loading wire:target="follow_up_attachment" class="text-yellow-400 text-sm mt-2">Mengunggah...</div>
            @error('follow_up_attachment') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end gap-4 pt-4">
            <button wire:click="$dispatch('closeModal')" type="button" class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white font-bold rounded-lg border border-white/30 text-xs uppercase">
                Batal
            </button>

            <button type="submit" class="inline-flex items-center px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-green-900 font-bold rounded-lg text-xs uppercase">
                <span wire:loading.remove>Kirim Laporan</span>
                <span wire:loading>Mengirim...</span>
            </button>
        </div>
    </form>
</div>