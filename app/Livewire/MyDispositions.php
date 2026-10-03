<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Disposition;
use Livewire\Attributes\Layout;
use Livewire\WithPagination; // Kita tambahkan paginasi

#[Layout('layouts.app')] // Atur layout utama
class MyDispositions extends Component
{
    use WithPagination; // Gunakan paginasi

    public $showFollowUpModal = false; // <-- TAMBAHKAN
    public $selectedDispositionId = null;

    protected $listeners = [
        'followUpSubmitted' => 'closeFollowUpModal',
        'closeModal' => 'closeFollowUpModal'
    ];

    public function openFollowUpModal($dispositionId)
    {
        $this->selectedDispositionId = $dispositionId;
        $this->showFollowUpModal = true;
    }

    public function closeFollowUpModal()
    {
        $this->selectedDispositionId = null;
        $this->showFollowUpModal = false;
    }

    public function render()
    {
        // Ambil semua tugas disposisi yang pending untuk user ini
        $pendingDispositions = Disposition::where('to_user_id', auth()->id())
                            ->where('status', 'Pending')
                            ->with('fromUser', 'incomingLetter') // Ambil data terkait
                            ->latest()
                            ->paginate(10); // Paginasi 10 tugas per halaman

        return view('livewire.my-dispositions', [
            'pendingDispositions' => $pendingDispositions
        ]);
    }
}