<div>
    <x-slot name="header">
        <h2 class="p-6 glass-morphism rounded-lg text-white">
            {{ __('Manajemen Surat Masuk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @can('isStaf')
            <div class="flex justify-end mb-4">
                <button wire:click="openModal(null)" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-green-900 font-bold rounded-lg transition-all duration-300 transform hover:scale-105 uppercase tracking-widest text-xs">
                    + Input Surat Baru
                </button>
            </div>
            @endcan
            
            @if(session('success'))
                <div class="mb-4 bg-green-500/20 border border-green-400 text-green-100 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-500/20 border border-red-400 text-red-100 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="space-y-4 mb-4">
                <input 
                    wire:model.live.debounce.300ms="search" 
                    type="text" 
                    placeholder="Cari berdasarkan No. Agenda, Asal Surat, Perihal..."
                    class="w-full px-4 py-3 bg-white/10 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent">

                <div class="flex flex-col md:flex-row gap-4">
                    <select wire:model.live="filterStatus" class="w-full md:flex-1 px-4 py-3 bg-white/10 border border-white/30 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
                        <option value="all" class="text-gray-800">Semua Status</option>
                        <option value="Pending" class="text-gray-800">Pending (Proses)</option>
                        <option value="Disetujui" class="text-gray-800">Disetujui</option>
                        <option value="Ditolak" class="text-gray-800">Ditolak</option>
                    </select>

                    <select wire:model.live="filterMonth" class="w-full md:flex-1 px-4 py-3 bg-white/10 border border-white/30 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
                        <option value="all" class="text-gray-800">Semua Waktu</option>
                        <option value="current_month" class="text-gray-800">Bulan Ini</option>
                        <option value="custom" class="text-gray-800">Rentang Waktu</option>
                    </select>
                </div>

                @if ($filterMonth === 'custom')
                    <div class="flex flex-col md:flex-row gap-4 p-4 bg-white/5 rounded-lg" wire:key="custom-date-filters">
                        <div class="flex-1">
                            <label for="filterDateStart" class="block text-sm font-medium text-green-100">Tanggal Mulai</label>
                            <input 
                                wire:model.live="filterDateStart" 
                                id="filterDateStart"
                                type="date" 
                                class="w-full mt-1 px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
                        </div>
                        <div class="flex-1">
                            <label for="filterDateEnd" class="block text-sm font-medium text-green-100">Tanggal Selesai</label>
                            <input 
                                wire:model.live="filterDateEnd" 
                                id="filterDateEnd"
                                type="date" 
                                class="w-full mt-1 px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
                        </div>
                    </div>
                @endif
            </div>


            <div class="bg-green-900/95 rounded-xl p-6 border border-white/20 shadow-lg overflow-hidden">
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-green-100">
                        <thead class="text-xs text-yellow-400 uppercase bg-white/10">
                            <tr>
                                <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="setSortBy('agenda_number')">
                                    No. Agenda @if($sortBy == 'agenda_number')<span>{{ $sortDir == 'ASC' ? '▲' : '▼' }}</span>@endif
                                </th>
                                <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="setSortBy('received_at')">
                                    Tgl. Diterima @if($sortBy == 'received_at')<span>{{ $sortDir == 'ASC' ? '▲' : '▼' }}</span>@endif
                                </th>
                                <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="setSortBy('sender_name')">
                                    Asal Surat @if($sortBy == 'sender_name')<span>{{ $sortDir == 'ASC' ? '▲' : '▼' }}</span>@endif
                                </th>
                                <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="setSortBy('subject')">
                                    Perihal @if($sortBy == 'subject')<span>{{ $sortDir == 'ASC' ? '▲' : '▼' }}</span>@endif
                                </th>
                                <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="setSortBy('status')">
                                    Status @if($sortBy == 'status')<span>{{ $sortDir == 'ASC' ? '▲' : '▼' }}</span>@endif
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($letters as $letter)
                                <tr class="border-b border-white/10 hover:bg-white/5">
                                    <td class="px-6 py-4">{{ $letter->agenda_number }}</td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($letter->received_at)->format('d M Y') }}</td>
                                    <td class="px-6 py-4">{{ $letter->sender_name }}</td>
                                    <td class="px-6 py-4">{{ $letter->subject }}</td>
                                    <td class="px-6 py-4">{{ $letter->status }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2"> 
                                            <button wire:click="showDetail({{ $letter->id }})" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-xs">
                                                Detail
                                            </button>
                                            
                                            @can('isStaf')
                                                <button wire:click="openModal({{ $letter->id }})" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg text-xs">
                                                    Edit
                                                </button>
                                                <button wire:click="askToDeleteLetter({{ $letter->id }})" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg text-xs">
                                                    Hapus
                                                </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center">
                                        @if($search)
                                            Tidak ada surat yang cocok dengan pencarian "{{ $search }}".
                                        @else
                                            Belum ada surat masuk.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $letters->links() }}
                </div>
            </div>
        </div>
    </div>

    @if($showLetterModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            @livewire('incoming-letter-form', ['letterId' => $editingLetterId], key('letter-form-'.$editingLetterId))
        </div>
    @endif

    @if($showDetailModal && $selectedLetter)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            <div class="bg-green-900/95 backdrop-blur-lg rounded-xl p-6 md:p-8 border border-white/20 shadow-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-white">Detail Surat Masuk</h3>
                    <button wire:click="closeDetailModal" class="text-white hover:text-red-300 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="space-y-4 text-white">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-green-200">No. Agenda</p>
                            <p class="font-semibold">{{ $selectedLetter->agenda_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-green-200">Kode Tracking</p>
                            <p class="font-semibold text-yellow-400">{{ $selectedLetter->tracking_code }}</p>
                        </div>
                    </div>
                    <div class="bg-white/10 rounded-lg p-4">
                        <h4 class="font-semibold text-white mb-2">Informasi Pengirim</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                            <p class="text-green-100"><strong>Nama:</strong> {{ $selectedLetter->sender_name }}</p>
                            <p class="text-green-100"><strong>No. Surat:</strong> {{ $selectedLetter->letter_number ?? '-' }}</p>
                            <p class="text-green-100"><strong>Tgl. Surat:</strong> {{ \Carbon\Carbon::parse($selectedLetter->letter_date)->format('d M Y') }}</p>
                            <p class="text-green-100"><strong>Tgl. Diterima:</strong> {{ \Carbon\Carbon::parse($selectedLetter->received_at)->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-green-200">Kategori</p>
                            <p class="font-semibold">{{ $selectedLetter->category }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-green-200">Status Saat Ini</p>
                            <p class="font-semibold">{{ $selectedLetter->status }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-green-200 mb-2">Perihal</p>
                        <p class="bg-white/10 rounded-lg p-3">{{ $selectedLetter->subject }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-green-200 mb-2">Petugas Penerima</p>
                        <p class="font-semibold">{{ $selectedLetter->createdBy->name ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-green-200 mb-2">Riwayat Disposisi</p>
                        <div class="space-y-3">
                            @forelse ($selectedLetter->dispositions->sortBy('created_at') as $disposition)
                                <div class="bg-white/10 rounded-lg p-3">
                                    <p class="text-sm text-green-100">
                                        {{ $disposition->created_at->format('d M Y, H:i') }}
                                    </p>
                                    <p class="text-sm text-white">
                                        Dari: <span class="font-semibold">{{ $disposition->fromUser->name ?? 'N/A' }}</span>
                                        ke: <span class="font-semibold">{{ $disposition->toUser->name ?? 'N/A' }}</span>
                                    </p>
                                    <p class="text-white bg-white/10 rounded-md p-2 mt-2">
                                       <span class="text-green-200 text-xs">Instruksi:</span> {{ $disposition->notes }}
                                    </p>

                                    @if($disposition->status == 'Selesai' && $disposition->follow_up_notes)
                                        <div class="border-t border-yellow-400/30 mt-3 pt-3">
                                            <p class="text-sm font-semibold text-yellow-400">Laporan Tindak Lanjut (oleh {{ $disposition->toUser->name ?? 'Staf' }}):</p>
                                            <p class="text-white bg-white/5 rounded-md p-2 mt-1">
                                                {{ $disposition->follow_up_notes }}
                                            </p>
                                            @if($disposition->follow_up_attachment)
                                                <a href="{{ route('storage.show', ['path' => $disposition->follow_up_attachment]) }}" target="_blank" class="text-xs text-yellow-400 underline mt-1 inline-block">
                                                    Lihat Lampiran Laporan
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="bg-white/10 rounded-lg p-3">
                                    <p class="text-green-100">Belum ada riwayat disposisi.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div>
                        <a href="{{ route('storage.show', ['path' => $selectedLetter->file_path]) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-green-900 font-bold rounded-lg text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Lihat/Download File Scan
                        </a>
                    </div>
                    
                    @can('isPimpinan')
                    <div class="border-t border-white/20 pt-4 text-right">
                        <button wire:click="openDispositionModal" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-all duration-300 text-xs">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path></svg>
                            Disposisi / Teruskan
                        </button>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
    @endif

    @if($showDispositionModal && $selectedLetter)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            <div class="bg-green-900/95 backdrop-blur-lg rounded-xl p-6 md:p-8 border border-white/20 shadow-lg max-w-lg w-full">

                <form wire:submit.prevent="saveDisposition" class="space-y-6">
                    <h3 class="text-xl font-bold text-white mb-4">Disposisi Surat</h3>

                    <div class="bg-white/10 rounded-lg p-4">
                        <p class="text-sm text-green-200">Perihal:</p>
                        <p class="font-semibold text-white">{{ $selectedLetter->subject }}</p>
                        <p class="text-sm text-green-200 mt-2">Dari:</p>
                        <p class="font-semibold text-white">{{ $selectedLetter->sender_name }}</p>
                    </div>

                    <div>
                        <label for="disposition_to_user_id" class="block font-medium text-sm text-green-100">Diteruskan Kepada:</label>
                        <select wire:model="disposition_to_user_id" id="disposition_to_user_id" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent mt-1">
                            <option value="" class="text-gray-800">Pilih Petugas</S-option>
                            @foreach($officers as $officer)
                                <option value="{{ $officer->id }}" class="text-gray-800">{{ $officer->name }}</option>
                            @endforeach
                        </select>
                        @error('disposition_to_user_id') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="disposition_notes" class="block font-medium text-sm text-green-100">Instruksi / Catatan:</label>
                        <textarea wire:model="disposition_notes" id="disposition_notes" rows="4" placeholder="Cth: Lakukan survei kelayakan ke rumah ybs." class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent mt-1"></textarea>
                        @error('disposition_notes') <span class="text-red-300 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-4 pt-4">
                        <button wire:click="closeDispositionModal" type="button" class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white font-bold rounded-lg border border-white/30 text-xs uppercase">
                            Batal
                        </button>
                        
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-green-900 font-bold rounded-lg text-xs uppercase">
                            <span wire:loading.remove>Kirim Disposisi</span>
                            <span wire:loading>Mengirim...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($confirmingLetterDeletionId)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            <div class="bg-green-900/95 rounded-xl p-6 border border-white/20 shadow-lg max-w-md w-full">
                
                <h3 class="text-xl font-bold text-white mb-4">Hapus Surat</h3>
                
                <p class="text-green-100 mb-6">
                    Anda yakin ingin menghapus surat ini? Semua data terkait (permohonan, disposisi, laporan) akan dihapus permanen.
                </p>

                <div class="flex justify-end gap-4">
                    <button wire:click="cancelDeleteLetter" type="button" class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-lg text-white font-bold rounded-lg border border-white/30 transition-all duration-300 uppercase tracking-widest text-xs">
                        Batal
                    </button>
                    
                    <x-danger-button wire:click="deleteLetter">
                        Ya, Hapus
                    </x-danger-button>
                </div>

            </div>
        </div>
    @endif

</div>