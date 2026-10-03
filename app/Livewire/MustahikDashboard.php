<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Request;

class MustahikDashboard extends Component
{
    public $user;
    public $stats = [];
    public $selectedRequestId = null;

    // Hapus $listeners 'closeModal'
    protected $listeners = [
        'requestSubmitted' => 'loadUserRequests', // Biarkan ini untuk jaga-jaga
        'openDetailModal' => 'openDetailModal', 
        'closeModal' => 'closeDetailModal'
    ];

    public function openDetailModal($requestId)
    {
        $this->selectedRequestId = $requestId;
    }

    public function closeDetailModal()
    {
        $this->selectedRequestId = null;
    }

    public function mount()
    {
        $this->user = auth()->user();
        $this->loadUserRequests();
    }

    // Hapus fungsi 'handleRequestSubmitted' dan 'closeRequestForm'
    
    public function loadUserRequests()
    {
        $requests = Request::where('user_id', $this->user->id)->get();
        $this->stats = [
            'total' => $requests->count(),
            'pending' => $requests->where('status', 'pending')->count(),
            'approved' => $requests->where('status', 'approved')->count(),
            'rejected' => $requests->where('status', 'rejected')->count(),
        ];
    }
    
    public function render()
    {
        return view('livewire.mustahik-dashboard');
    }
}