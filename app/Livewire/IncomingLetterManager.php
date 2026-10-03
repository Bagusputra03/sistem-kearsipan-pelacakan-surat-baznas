<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\IncomingLetter;
use App\Models\User;
use App\Models\Disposition; // Pastikan ini ada
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage; // Pastikan ini ada
use Carbon\Carbon; // Pastikan ini ada

#[Layout('layouts.app')]
class IncomingLetterManager extends Component
{
    use WithPagination;

    // Properti Modal
    public $showLetterModal = false;
    public $showDetailModal = false;
    public $showDispositionModal = false;
    
    // Properti Data
    public $selectedLetter = null;
    public $editingLetterId = null; // Untuk Edit
    public $confirmingLetterDeletionId = null; // Untuk Hapus

    // Properti Disposisi
    public $disposition_notes = '';
    public $disposition_to_user_id = null;

    // Properti Search & Sort
    public $search = '';
    public $sortBy = 'received_at';
    public $sortDir = 'DESC';
    public $filterStatus = 'all';
    public $filterMonth = 'all';
    public $filterDateStart = null;
    public $filterDateEnd = null;

    protected $listeners = [
        'letterSaved' => 'closeModal', // <-- Dari form C/U
        'closeModal' => 'closeModal', // <-- Dari tombol Batal
        'requestUpdated' => 'refreshModalData'
    ];

    // --- FUNGSI MODAL (CREATE/UPDATE) ---
    public function openModal($letterId = null)
    {
        $this->editingLetterId = $letterId;
        $this->showLetterModal = true;
    }

    public function closeModal()
    {
        $this->showLetterModal = false;
        $this->editingLetterId = null;
    }

    // --- FUNGSI MODAL (DETAIL) ---
    public function showDetail($letterId)
    {
        $this->selectedLetter = IncomingLetter::with(
                                    'createdBy', 
                                    'dispositions.fromUser', 
                                    'dispositions.toUser'
                                )->find($letterId);
        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedLetter = null;
    }
    
    public function refreshModalData()
    {
        if ($this->showDetailModal && $this->selectedLetter) {
            $this->selectedLetter = $this->selectedLetter->fresh([
                                        'createdBy', 
                                        'dispositions.fromUser', 
                                        'dispositions.toUser'
                                    ]);
        }
    }

    // --- FUNGSI MODAL (DISPOSISI) ---
    public function openDispositionModal()
    {
        $this->showDispositionModal = true;
    }

    public function closeDispositionModal()
    {
        $this->showDispositionModal = false;
        $this->reset(['disposition_notes', 'disposition_to_user_id']);
    }

    public function saveDisposition()
    {
        $this->validate([
            'disposition_to_user_id' => 'required|exists:users,id',
            'disposition_notes' => 'required|string|min:5',
        ]);

        Disposition::create([
            'incoming_letter_id' => $this->selectedLetter->id,
            'from_user_id' => auth()->id(),
            'to_user_id' => $this->disposition_to_user_id,
            'notes' => $this->disposition_notes,
            'status' => 'Pending',
        ]);

        $officer = User::find($this->disposition_to_user_id);
        $this->selectedLetter->update([
            'status' => 'Didisposisikan ke ' . $officer->name
        ]);
        
        $this->closeDispositionModal();
        $this->closeDetailModal();
        session()->flash('success', 'Surat berhasil didisposisikan ke ' . $officer->name);
    }

    // --- FUNGSI HAPUS (UNTUK STAF) ---
    public function askToDeleteLetter($letterId)
    {
        if (auth()->user()->is_pimpinan) {
            session()->flash('error', 'Pimpinan tidak dapat menghapus surat.');
            return;
        }
        $this->confirmingLetterDeletionId = $letterId;
    }

    public function cancelDeleteLetter()
    {
        $this->confirmingLetterDeletionId = null;
    }

    public function deleteLetter()
    {
        if (auth()->user()->is_pimpinan) {
            return;
        }

        $letter = IncomingLetter::with(['requestData', 'dispositions'])->find($this->confirmingLetterDeletionId);

        if ($letter) {
            Storage::disk('local')->delete($letter->file_path);
            foreach ($letter->dispositions as $disposition) {
                if ($disposition->follow_up_attachment) {
                    Storage::disk('local')->delete($disposition->follow_up_attachment);
                }
            }
            
            $letter->dispositions()->delete();
            $letter->requestData()->delete();
            $letter->delete();
            
            session()->flash('success', 'Surat dan semua data terkait berhasil dihapus.');
        }
        
        $this->confirmingLetterDeletionId = null;
    }
    // --- AKHIR FUNGSI HAPUS ---
    
    // --- FUNGSI FILTER/SORT ---
    public function updatingSearch() { $this->resetPage(); }
    public function updatedFilterStatus() { $this->resetPage(); }
    public function updatedFilterMonth()
    {
        if ($this->filterMonth !== 'custom') {
            $this->filterDateStart = null;
            $this->filterDateEnd = null;
        }
        $this->resetPage();
    }
    public function updatedFilterDateStart() { $this->resetPage(); }
    public function updatedFilterDateEnd() { $this->resetPage(); }

    public function setSortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDir = ($this->sortDir === 'ASC') ? 'DESC' : 'ASC';
        } else {
            $this->sortDir = 'ASC';
        }
        $this->sortBy = $field;
    }
    
    
    public function render()
    {
        // Ambil daftar petugas (Staf) untuk dropdown disposisi
        $officers = User::where('userType', 'officer')
                        ->where('is_pimpinan', false) // Hanya Staf
                        ->where('id', '!=', auth()->id())
                        ->orderBy('name', 'asc')
                        ->get();
                        
        $query = IncomingLetter::with('createdBy')
            ->where(function($query) {
                $query->where('agenda_number', 'like', '%'.$this->search.'%')
                      ->orWhere('sender_name', 'like', '%'.$this->search.'%')
                      ->orWhere('subject', 'like', '%'.$this->search.'%')
                      ->orWhere('status', 'like', '%'.$this->search.'%')
                      ->orWhere('tracking_code', 'like', '%'.$this->search.'%');
            })
            ->when($this->filterStatus !== 'all', function ($query) {
                if ($this->filterStatus === 'Disetujui' || $this->filterStatus === 'Ditolak') {
                    $query->where('status', $this->filterStatus);
                } elseif ($this->filterStatus === 'Pending') {
                    $query->whereNotIn('status', ['Disetujui', 'Ditolak']);
                }
            })
            ->when($this->filterMonth === 'current_month', function ($query) {
                $query->whereMonth('received_at', now()->month)
                      ->whereYear('received_at', now()->year);
            })
            ->when($this->filterMonth === 'custom' && $this->filterDateStart && $this->filterDateEnd, function ($query) {
                try {
                    $endDate = Carbon::parse($this->filterDateEnd)->endOfDay();
                    $query->whereBetween('received_at', [$this->filterDateStart, $endDate]);
                } catch (\Exception $e) { }
            })
            ->orderBy($this->sortBy, $this->sortDir);
            
        $letters = $query->paginate(10);
                        
        return view('livewire.incoming-letter-manager', [
            'letters' => $letters,
            'officers' => $officers
        ]);
    }
}