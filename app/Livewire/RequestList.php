<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Request;
use Livewire\WithPagination;

class RequestList extends Component
{
    use WithPagination;

    public $userType;
    public $filter = 'all';
    public $search = '';
    public $sortBy = 'created_at_desc';

    protected $listeners = [
        'refreshList' => '$refresh',
        'requestUpdated' => '$refresh',
        'letterSaved' => '$refresh',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatedSortBy()
    {
         $this->resetPage();
    }

    public function mount($userType)
    {
        $this->userType = $userType;
    }

    public function updateRequestStatus($requestId, $newStatus, $comments = '')
    {
        if ($this->userType !== 'officer') {
            return;
        }

        $request = Request::find($requestId);
        if ($request) {
            $request->status = $newStatus;
            $request->reviewedAt = now();
            $request->reviewComments = $comments;
            $request->save();

            if ($request->incomingLetter) {
                $letterStatus = $this->getStatusText($newStatus); 
                $request->incomingLetter->update(['status' => $letterStatus]);
            }

            $this->dispatch('requestUpdated'); 
        }

        // Jangan tutup modal dari sini lagi
    }

    // Fungsi helper ini boleh dihapus dari sini, 
    // tapi biarkan saja juga tidak apa-apa (untuk tombol Setujui/Tolak)
    public function getStatusColor($status)
    {
        switch ($status) {
            case 'pending': return 'bg-yellow-500/20 text-yellow-300 border-yellow-400';
            case 'approved': return 'bg-green-500/20 text-green-300 border-green-400';
            case 'rejected': return 'bg-red-500/20 text-red-300 border-red-400';
            default: return 'bg-gray-500/20 text-gray-300 border-gray-400';
        }
    }

    public function getStatusText($status)
    {
        switch ($status) {
            case 'pending': return 'Menunggu Review';
            case 'approved': return 'Disetujui';
            case 'rejected': return 'Ditolak';
            default: return 'Tidak Diketahui';
        }
    }

    public function render()
    {
        $query = Request::with('user')
            ->when($this->userType === 'mustahik', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->when($this->filter !== 'all', function ($q) {
                $q->where('status', $this->filter);
            })
            ->where(function ($q) {
                $q->where('requestType', 'like', '%'.$this->search.'%')
                  ->orWhere('reason', 'like', '%'.$this->search.'%')
                  ->orWhere('amount', 'like', '%'.$this->search.'%');

                if ($this->userType === 'officer') {
                    $q->orWhereHas('user', function ($userQuery) {
                        $userQuery->where('name', 'like', '%'.$this->search.'%');
                    });
                }
            });

        if ($this->sortBy === 'amount_asc') {
            $query->orderBy('amount', 'ASC');
        } elseif ($this->sortBy === 'amount_desc') {
            $query->orderBy('amount', 'DESC');
        } elseif ($this->sortBy === 'created_at_asc') {
            $query->orderBy('created_at', 'ASC');
        } else {
            $query->orderBy('created_at', 'DESC');
        }

        $allUserRequests = Request::with('user')
            ->when($this->userType === 'mustahik', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->get();

        $counts = [
            'all' => $allUserRequests->count(),
            'pending' => $allUserRequests->where('status', 'pending')->count(),
            'approved' => $allUserRequests->where('status', 'approved')->count(),
            'rejected' => $allUserRequests->where('status', 'rejected')->count(),
        ];

        return view('livewire.request-list', [
            'requests' => $query->paginate(5), 
            'counts' => $counts
        ]);
    }
}