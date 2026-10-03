<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\DocumentArchive;
use Illuminate\Support\Facades\Storage;

class DocumentArchiveForm extends Component
{
    use WithFileUploads;

    public $documentId = null;
    public $isEditMode = false;

    // Properti Model
    public $title;
    public $description;
    public $document_date;
    public $file;
    public $existingFilePath = null; // Untuk menyimpan path file lama saat edit

    public function mount($documentId = null)
    {
        if ($documentId) {
            $this->documentId = $documentId;
            $this->isEditMode = true;
            $doc = DocumentArchive::findOrFail($documentId);

            $this->title = $doc->title;
            $this->description = $doc->description;
            // Format tanggal agar sesuai dengan input type="date"
            $this->document_date = $doc->document_date->format('Y-m-d'); 
            $this->existingFilePath = $doc->file_path;
        }
    }

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document_date' => 'required|date',
            // Buat file opsional saat mode Edit
            'file' => ($this->isEditMode ? 'nullable' : 'required') . '|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120',
        ];
    }

    public function save()
    {
        $data = $this->validate();

        // Logika penanganan file
        if ($this->file) {
            // 1. Upload file baru
            $data['file_path'] = $this->file->store('public/arsip_internal');
            // 2. Hapus file lama jika ini adalah mode edit
            if ($this->isEditMode && $this->existingFilePath) {
                Storage::disk('local')->delete($this->existingFilePath);
            }
        } else {
            // Jika tidak ada file baru, jangan ubah path file
            unset($data['file']); // Hapus 'file' dari data yang akan disimpan
        }

        if ($this->isEditMode) {
            // --- UPDATE ---
            // Temukan dokumen dan perbarui. 
            // Kita tidak perlu menyentuh 'uploaded_by_user_id' di sini.
            $doc = DocumentArchive::find($this->documentId);
            $doc->update($data);
            $message = 'Arsip berhasil diperbarui.';

        } else {
            // --- CREATE ---
            // TAMBAHKAN ID PENGGUNA SEBELUM MEMBUAT BARU
            $data['uploaded_by_user_id'] = auth()->id(); 
            DocumentArchive::create($data);
            $message = 'Arsip berhasil diunggah.';
        }

        // Kirim event
        $this->dispatch('archiveSaved');
        session()->flash('success', $this->isEditMode ? 'Arsip berhasil diperbarui.' : 'Arsip berhasil diunggah.');
    }

    public function render()
    {
        return view('livewire.document-archive-form');
    }
}