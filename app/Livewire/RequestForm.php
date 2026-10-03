<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Request;

class RequestForm extends Component
{
    public $requestType = '';
    public $amount;
    public $reason = '';
    public $urgency = 'normal';
    public $familyMembers;
    public $monthlyIncome;
    public $jobStatus = '';
    public $description = '';

    // Pastikan semua field divalidasi
    protected $rules = [
        'requestType' => 'required',
        'amount' => 'required|numeric|min:1',
        'reason' => 'required|string|min:10',
        'urgency' => 'required|in:normal,urgent,critical',
        'familyMembers' => 'required|integer|min:1',
        'monthlyIncome' => 'required|numeric|min:0',
        'jobStatus' => 'required|string',
        'description' => 'nullable|string',
    ];

    public function submit()
    {
        $data = $this->validate();

        Request::create(array_merge($data, [
            'user_id' => auth()->id(),
            'status' => 'pending',
            'amount' => (float)$this->amount,
            'monthlyIncome' => (float)$this->monthlyIncome,
            'familyMembers' => (int)$this->familyMembers,
        ]));

        // PERUBAHAN DI SINI: Gunakan $this->dispatch()
        
        // 1. Kirim event untuk menutup modal (MustahikDashboard akan menangkap ini)
        $this->dispatch('closeModal'); 
        
        // 2. Kirim event untuk me-refresh RequestList (RequestList akan menangkap ini)
        $this->dispatch('refreshList');
        
        // 3. Kirim event untuk me-refresh MustahikDashboard (stats)
        $this->dispatch('requestSubmitted');
    }

    public function render()
    {
        return view('livewire.request-form');
    }
}