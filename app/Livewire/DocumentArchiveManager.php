<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\DocumentArchive;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
class DocumentArchiveManager extends Component
{
    use WithPagination;

    public $showArchiveModal = false;
    public $editingDocumentId = null;

    public $confirmingDocumentDeletionId = null;

    protected $listeners = [
        'archiveSaved' => 'closeModal',
        'closeModal' => 'closeModal'
    ];

    public function openModal($documentId = null)
    {
        $this->editingDocumentId = $documentId;
        $this->showArchiveModal = true;
    }

    public function closeModal()
    {
        $this->showArchiveModal = false;
        $this->editingDocumentId = null;
    }

    // --- AWAL MODIFIKASI FUNGSI HAPUS ---

    /**
     * Dipanggil saat tombol "Hapus" diklik.
     */
    public function askToDeleteDocument($id)
    {
        $this->confirmingDocumentDeletionId = $id;
    }

    /**
     * Dipanggil saat tombol "Batal" di modal konfirmasi diklik.
     */
    public function cancelDeleteDocument()
    {
        $this->confirmingDocumentDeletionId = null;
    }

    /**
     * Dipanggil saat tombol "Ya, Hapus" diklik.
     */
    public function deleteDocument() // Ganti nama dari 'confirmDeleteDocument'
    {
        if (auth()->user()->is_pimpinan) {
            return;
        }

        $doc = DocumentArchive::find($this->confirmingDocumentDeletionId);

        if ($doc) {
            Storage::disk('local')->delete($doc->file_path);
            $doc->delete();
            session()->flash('success', 'Dokumen berhasil dihapus.');
        }

        $this->confirmingDocumentDeletionId = null; // Tutup modal
    }


    public function render()
    {
        $archives = DocumentArchive::with('uploader')
                        ->latest()
                        ->paginate(10);

        return view('livewire.document-archive-manager', [
            'archives' => $archives
        ]);
    }
}