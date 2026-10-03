<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads; // <-- Untuk upload file
use App\Models\Disposition;

class DispositionFollowUpForm extends Component
{
    use WithFileUploads;

    public Disposition $disposition; // Kita kirim seluruh model

    public $follow_up_notes;
    public $follow_up_attachment;

    protected $rules = [
        'follow_up_notes' => 'required|string|min:10',
        'follow_up_attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // 2MB Max
    ];

    public function mount($dispositionId) // Ganti nama parameter
    {
        // Temukan objek Disposition berdasarkan ID yang dikirim
        $this->disposition = Disposition::find($dispositionId);
    }

    public function saveFollowUp()
    {
        $this->validate();

        $attachmentPath = null;
        if ($this->follow_up_attachment) {
            $attachmentPath = $this->follow_up_attachment->store('public/tindak_lanjut');
        }

        // 1. Update tugas disposisi
        $this->disposition->update([
            'status' => 'Selesai',
            'follow_up_notes' => $this->follow_up_notes,
            'follow_up_attachment' => $attachmentPath,
        ]);

        // 2. Update status surat utama untuk pelacakan
        $this->disposition->incomingLetter->update([
            'status' => 'Tindak Lanjut Selesai (oleh ' . auth()->user()->name . ')'
        ]);

        // 3. Beri tahu komponen lain untuk me-refresh
        $this->dispatch('requestUpdated'); // Refresh RequestList & Dashboard
        $this->dispatch('followUpSubmitted'); // Tutup modal ini

        session()->flash('success', 'Laporan tindak lanjut berhasil dikirim.');
    }

    public function render()
    {
        return view('livewire.disposition-follow-up-form');
    }
}