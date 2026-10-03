<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class UserManagement extends Component
{
    public $showUserModal = false;
    public $editingUserId = null;

    public $confirmingUserDeletionId = null;

    // Listener untuk menutup modal dan refresh
    protected $listeners = [
        'userSaved' => 'closeModal',
        'closeModal' => 'closeModal'
    ];

    public function openModal($userId = null)
    {
        $this->editingUserId = $userId;
        $this->showUserModal = true;
    }

    public function closeModal()
    {
        $this->showUserModal = false;
        $this->editingUserId = null;
    }

    // Dipanggil saat tombol "Hapus" diklik
    public function askToDeleteUser($userId)
    {
        $this->confirmingUserDeletionId = $userId;
    }

    // Dipanggil saat tombol "Batal" di modal konfirmasi diklik
    public function cancelDeleteUser()
    {
        $this->confirmingUserDeletionId = null;
    }

    public function confirmDeleteUser()
    {
        if ($this->confirmingUserDeletionId == auth()->id()) {
            session()->flash('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            $this->confirmingUserDeletionId = null; // Tutup modal
            return;
        }

        User::find($this->confirmingUserDeletionId)->delete();
        session()->flash('success', 'Pengguna berhasil dihapus.');
        
        $this->confirmingUserDeletionId = null; // Tutup modal
    }

    public function render()
    {
        $users = User::orderBy('userType', 'asc')->orderBy('name', 'asc')->get();
        return view('livewire.user-management', [
            'users' => $users
        ]);
    }
}