<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\IncomingLetter;
use App\Models\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage; // Penting untuk file handling

class IncomingLetterForm extends Component
{
    use WithFileUploads;

    // Properti untuk Mode
    public $letterId = null;
    public $isEditMode = false;
    public $existingFilePath = null;

    // Properti Surat Masuk
    public $sender_name;
    public $letter_number;
    public $letter_date;
    public $subject;
    public $category = 'Permohonan Bantuan';
    public $file_scan;

    // Properti Mustahik
    public $nik_mustahik;
    public $foundMustahik = null;
    public $mustahik_name;
    public $mustahik_phone;
    public $mustahik_address;
    
    // Properti Permohonan
    public $requestType = '';
    public $amount;
    public $reason = '';
    public $urgency = 'normal';
    public $familyMembers;
    public $monthlyIncome;
    public $jobStatus = '';
    public $description = '';

    /**
     * Mount (Load data jika ini mode Edit)
     */
    public function mount($letterId = null)
    {
        if ($letterId) {
            $this->letterId = $letterId;
            $this->isEditMode = true;
            $letter = IncomingLetter::with('requestData.user')->findOrFail($letterId);

            // 1. Isi data Surat Masuk
            $this->sender_name = $letter->sender_name;
            $this->letter_number = $letter->letter_number;
            $this->letter_date = $letter->letter_date->format('Y-m-d'); // Format untuk input date
            $this->subject = $letter->subject;
            $this->category = $letter->category;
            $this->existingFilePath = $letter->file_path;

            // 2. Jika ini permohonan, isi data permohonan
            if ($letter->category === 'Permohonan Bantuan' && $letter->requestData) {
                $request = $letter->requestData;
                $this->requestType = $request->requestType;
                $this->amount = $request->amount;
                $this->reason = $request->reason;
                $this->urgency = $request->urgency;
                $this->familyMembers = $request->familyMembers;
                $this->monthlyIncome = $request->monthlyIncome;
                $this->jobStatus = $request->jobStatus;
                $this->description = $request->description;

                // 3. Isi data Mustahik
                if ($request->user) {
                    $this->foundMustahik = $request->user;
                    $this->nik_mustahik = $request->user->nik;
                    $this->mustahik_name = $request->user->name;
                    $this->mustahik_phone = $request->user->phone;
                    $this->mustahik_address = $request->user->address;
                }
            }
        }
    }
    
    public function updatedCategory($value)
    {
        if ($value !== 'Permohonan Bantuan') {
            $this->resetFormPermohonan();
        }
    }

    public function searchMustahik()
    {
        $this->validate(['nik_mustahik' => 'required|digits:16']);
        $user = User::where('nik', $this->nik_mustahik)->where('userType', 'mustahik')->first();

        if ($user) {
            $this->foundMustahik = $user;
            $this->mustahik_name = $user->name;
            $this->mustahik_phone = $user->phone;
            $this->mustahik_address = $user->address;
        } else {
            $this->foundMustahik = false;
            $this->reset(['mustahik_name', 'mustahik_phone', 'mustahik_address']);
        }
    }

    protected function rules()
    {
        $rules = [
            'sender_name' => 'required|string|max:255',
            'letter_number' => 'nullable|string|max:100',
            'letter_date' => 'required|date',
            'subject' => 'required|string|max:255',
            'category' => 'required|string',
            // Buat file opsional saat edit
            'file_scan' => ($this->isEditMode ? 'nullable' : 'required') . '|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
        
        if ($this->category === 'Permohonan Bantuan') {
            $rules['nik_mustahik'] = 'required|digits:16';
            
            // Hanya validasi jika NIK baru
            if ($this->foundMustahik === false) {
                $rules['mustahik_name'] = 'required|string|max:255';
                $rules['mustahik_phone'] = 'required|string|max:15';
                $rules['mustahik_address'] = 'required|string';
            }
            
            // Aturan validasi permohonan
            $rules['requestType'] = 'required';
            $rules['amount'] = 'required|numeric|min:1';
            $rules['reason'] = 'required|string|min:10';
            $rules['urgency'] = 'required|in:normal,urgent,critical';
            $rules['familyMembers'] = 'required|integer|min:1';
            $rules['monthlyIncome'] = 'required|numeric|min:0';
            $rules['jobStatus'] = 'required|string';
            $rules['description'] = 'nullable|string';
        }
        return $rules;
    }

    public function save()
    {
        $data = $this->validate();

        // --- Logika File ---
        $filePath = $this->existingFilePath; // Default: gunakan file lama
        if ($this->file_scan) {
            // Jika ada file baru, upload
            $filePath = $this->file_scan->store('public/surat_masuk');
            // Hapus file lama jika ini mode edit dan file lama ada
            if ($this->isEditMode && $this->existingFilePath) {
                Storage::disk('local')->delete($this->existingFilePath);
            }
        }

        // --- Logika Simpan / Update ---
        $letterData = [
            'sender_name' => $this->sender_name,
            'letter_number' => $this->letter_number,
            'letter_date' => $this->letter_date,
            'subject' => $this->subject,
            'category' => $this->category,
            'file_path' => $filePath,
            'created_by_user_id' => auth()->id(), // Set/reset petugas yg mengedit
        ];
        
        if ($this->isEditMode) {
            // --- UPDATE ---
            $letter = IncomingLetter::find($this->letterId);
            $letter->update($letterData);
            $message = 'Surat berhasil diperbarui.';
        } else {
            // --- CREATE ---
            $now = Carbon::now();
            
            // 1. Cari surat terakhir di bulan & tahun ini
            $lastLetter = IncomingLetter::whereYear('received_at', $now->year)
                                        ->whereMonth('received_at', $now->month)
                                        ->orderBy('agenda_number', 'desc')
                                        ->first();
            
            $newCount = 1; // Default jika ini surat pertama di bulan ini
            if ($lastLetter) {
                // Ambil 3 digit pertama (cth: '010' dari '010/BAZNAS-KD/11/2025')
                $lastNumber = (int) substr($lastLetter->agenda_number, 0, 3);
                $newCount = $lastNumber + 1;
            }

            // 2. Format Nomor Agenda baru
            $letterData['agenda_number'] = sprintf('%03d', $newCount) . '/BAZNAS-KD/' . $now->format('m/Y');
            // === AKHIR PERBAIKAN ===

            $letterData['tracking_code'] = 'BZN-' . strtoupper(Str::random(6));
            $letterData['received_at'] = $now; // Gunakan $now
            $letterData['status'] = 'Diterima Petugas';
            
            $letter = IncomingLetter::create($letterData);
            $message = 'Surat baru berhasil diarsipkan. Kode Tracking: ' . $letter->tracking_code;
        }

        // --- Logika Simpan / Update Permohonan Bantuan ---
        if ($this->category === 'Permohonan Bantuan') {
            $mustahikId = null;
            $passwordDefault = '12345678';
            
            if ($this->foundMustahik) { // Jika NIK ditemukan
                $mustahikId = $this->foundMustahik->id;
            } elseif ($this->foundMustahik === false) { // Jika NIK baru
                $newMustahik = User::create([
                    'name' => $this->mustahik_name,
                    'email' => $this->nik_mustahik . '@baznas.local',
                    'password' => Hash::make($passwordDefault),
                    'nik' => $this->nik_mustahik,
                    'phone' => $this->mustahik_phone,
                    'address' => $this->mustahik_address,
                    'userType' => 'mustahik',
                ]);
                $mustahikId = $newMustahik->id;
                if (!$this->isEditMode) { // Hanya tampilkan info pass saat Buat Baru
                    $message .= " | Login Mustahik: NIK (" . $this->nik_mustahik . ") & Password Default (" . $passwordDefault . ")";
                }
            }
            
            Request::updateOrCreate(
                ['incoming_letter_id' => $letter->id], // Cari berdasarkan ID surat
                [ // Data untuk di-update/create
                    'user_id' => $mustahikId,
                    'status' => $letter->requestData->status ?? 'pending', // Jaga status lama jika ada
                    'requestType' => $this->requestType,
                    'amount' => $this->amount,
                    'reason' => $this->reason,
                    'urgency' => $this->urgency,
                    'familyMembers' => $this->familyMembers,
                    'monthlyIncome' => $this->monthlyIncome,
                    'jobStatus' => $this->jobStatus,
                    'description' => $this->description,
                ]
            );

            // Set status "Diproses" jika surat baru dibuat
            if (!$this->isEditMode && $letter->status == 'Diterima Petugas') {
                $letter->status = 'Permohonan Diproses';
                $letter->save();
            }
        }
        
        $this->dispatch('letterSaved'); // Kirim event ke induk
        session()->flash('success', $message);
    }
    
    private function resetFormPermohonan()
    {
        $this->reset([
            'nik_mustahik', 'foundMustahik', 'mustahik_name', 'mustahik_phone', 'mustahik_address',
            'requestType', 'amount', 'reason', 'urgency', 'familyMembers', 'monthlyIncome', 'jobStatus', 'description'
        ]);
    }

    public function render()
    {
        return view('livewire.incoming-letter-form');
    }
}