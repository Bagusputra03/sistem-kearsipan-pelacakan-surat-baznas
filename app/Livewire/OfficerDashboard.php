<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Request;
use App\Models\User;
use App\Models\Disposition;

class OfficerDashboard extends Component
{
    public $stats = [];
    public $selectedRequestId = null;

    protected $listeners = [
        'requestUpdated' => 'loadData',
        'letterSaved' => 'loadData',
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
        $this->loadData();
    }

    public function loadData()
    {
        $allRequests = Request::all();
        $allUsers = User::where('userType', 'mustahik')->get();
        
        $totalAmount = $allRequests
            ->where('status', 'approved')
            ->sum('amount');

        $this->stats = [
            'totalRequests' => $allRequests->count(),
            'pendingRequests' => $allRequests->where('status', 'pending')->count(),
            'approvedRequests' => $allRequests->where('status', 'approved')->count(),
            'rejectedRequests' => $allRequests->where('status', 'rejected')->count(),
            'totalUsers' => $allUsers->count(),
            'totalAmount' => $totalAmount
        ];
    }

    public function render()
    {
        return view('livewire.officer-dashboard', [
            'stats' => $this->stats
        ]);
    }
}