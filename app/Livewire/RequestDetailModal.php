<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Request;

class RequestDetailModal extends Component
{
    public Request $request; // Otomatis memuat Request berdasarkan ID

    public function mount($requestId)
    {
        // Muat data Request lengkap dengan data User (Pemohon)
        $this->request = Request::with('user')->findOrFail($requestId);
    }

    // Kita pindahkan fungsi helper getStatus ke sini
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
        return view('livewire.request-detail-modal');
    }
}