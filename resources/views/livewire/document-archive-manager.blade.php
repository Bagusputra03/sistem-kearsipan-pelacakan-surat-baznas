<div>
    <x-slot name="header">
        <h2 class="p-6 glass-morphism rounded-lg text-white">
            {{ __('Arsip Dokumen Internal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @can('isStaf')
            <div class="flex justify-end mb-4">
                <button wire:click="openModal(null)" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-green-900 font-bold rounded-lg transition-all duration-300 transform hover:scale-105 uppercase tracking-widest text-xs">
                    + Upload Arsip Baru
                </button>
            </div>
            @endcan

            @if(session('success'))
                <div class="mb-4 bg-green-500/20 border border-green-400 text-green-100 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-green-900/95 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg overflow-hidden">
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-green-100">
                        <thead class="text-xs text-yellow-400 uppercase bg-white/10">
                            <tr>
                                <th scope="col" class="px-6 py-3">Judul Dokumen</th>
                                <th scope="col" class="px-6 py-3">Tanggal Dokumen</th>
                                <th scope="col" class="px-6 py-3">Petugas Pengunggah</th>
                                <th scope="col" class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($archives as $doc)
                                <tr class="border-b border-white/10 hover:bg-white/5">
                                    <td class="px-6 py-4">{{ $doc->title }}</td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($doc->document_date)->format('d M Y') }}</td>
                                    <td class="px-6 py-4">{{ $doc->uploader->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            <a href="{{ route('storage.show', ['path' => $doc->file_path]) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white font-bold rounded-lg border border-white/30 text-xs">
                                                Lihat
                                            </a>

                                            @can('isStaf')
                                                <button wire:click="openModal({{ $doc->id }})" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-xs">
                                                    Edit
                                                </button>

                                                <button wire:click="askToDeleteDocument({{ $doc->id }})" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg text-xs">
                                                    Hapus
                                                </button>
                                                @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center">Belum ada arsip internal.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $archives->links() }}
                </div>
            </div>
        </div>
    </div>

    @if($showArchiveModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            @livewire('document-archive-form', ['documentId' => $editingDocumentId], key($editingDocumentId))
        </div>
    @endif

    @if($confirmingDocumentDeletionId)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            <div class="bg-green-900/95 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg max-w-md w-full">

                <h3 class="text-xl font-bold text-white mb-4">Hapus Arsip</h3>

                <p class="text-green-100 mb-6">
                    Anda yakin ingin menghapus arsip ini? File yang ter-upload juga akan dihapus secara permanen.
                </p>

                <div class="flex justify-end gap-4">
                    <button wire:click="cancelDeleteDocument" type="button" class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-lg text-white font-bold rounded-lg border border-white/30 transition-all duration-300 uppercase tracking-widest text-xs">
                        Batal
                    </button>

                    <x-danger-button wire:click="deleteDocument">
                        Ya, Hapus
                    </x-danger-button>
                </div>

            </div>
        </div>
    @endif
    </div>