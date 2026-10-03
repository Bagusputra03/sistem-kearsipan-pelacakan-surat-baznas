<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserForm extends Component
{
    public $userId = null;
    public $name, $email, $nik, $phone, $address, $userType, $password, $password_confirmation;
    public $is_pimpinan = false;
    
    public $isEditMode = false;

    public function mount($userId = null)
    {
        if ($userId) {
            $this->userId = $userId;
            $this->isEditMode = true;
            $user = User::find($userId);
            
            $this->name = $user->name;
            $this->email = $user->email;
            $this->nik = $user->nik;
            $this->phone = $user->phone;
            $this->address = $user->address;
            $this->userType = $user->userType;
            $this->is_pimpinan = $user->is_pimpinan;
        }
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($this->userId)],
            'nik' => ['required', 'string', 'digits:16', Rule::unique(User::class)->ignore($this->userId)],
            'phone' => ['required', 'string', 'max:15'],
            'address' => ['required', 'string'],
            'userType' => ['required', Rule::in(['mustahik', 'officer'])],
            'is_pimpinan' => 'required|boolean',
            'password' => [$this->isEditMode ? 'nullable' : 'required', 'confirmed', Rules\Password::defaults()],
        ];
    }

    public function save()
    {
        // Pastikan 'is_pimpinan' = false jika userType bukan 'officer'
        if ($this->userType !== 'officer') {
            $this->is_pimpinan = false;
        }
        
        $data = $this->validate();

        if ($this->isEditMode) {
            // Update
            $user = User::find($this->userId);
            $updateData = $this->only(['name', 'email', 'nik', 'phone', 'address', 'userType', 'is_pimpinan']);
            
            if ($this->password) {
                $updateData['password'] = Hash::make($this->password);
            }
            $user->update($updateData);

        } else {
            // Create
            $data['password'] = Hash::make($this->password);
            User::create($data);
        }

        // Kirim event untuk menutup modal dan me-refresh daftar
        $this->dispatch('userSaved');
    }

    public function render()
    {
        return view('livewire.user-form');
    }
}