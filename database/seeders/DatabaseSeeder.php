<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\IncomingLetter;
use App\Models\Request;
use App\Models\Disposition;
use App\Models\DocumentArchive;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. BUAT AKUN PIMPINAN (ADMIN UTAMA ANDA)
        $pimpinan = User::create([
            'name' => 'Pimpinan BAZNAS',
            'email' => 'pimpinan@baznas.com',
            'nik' => '1234567890123456',
            'phone' => '081234567890',
            'address' => 'Kantor BAZNAS Kudus',
            'password' => Hash::make('pimpinan12345'), // Ganti 'password' dengan password Anda
            'userType' => 'officer',
            'is_pimpinan' => true, // Ini adalah Pimpinan
        ]);

        // 2. BUAT 1 AKUN STAF (PETUGAS)
        $staf1 = User::create([
            'name' => 'Admin Staf',
            'email' => 'staf@baznas.com',
            'nik' => '0123456789012345',
            'phone' => '081234567891',
            'address' => 'Kantor BAZNAS Kudus',
            'password' => Hash::make('staf12345'),
            'userType' => 'officer',
            'is_pimpinan' => false, // Ini adalah Staf
        ]);

        // 3. BUAT 3 AKUN MUSTAHIK
        $mustahik1 = User::create([
            'name' => 'Ahmad Subagyo',
            'email' => '1111222233334444@baznas.local',
            'nik' => '1111222233334444',
            'phone' => '085111111111',
            'address' => 'Desa Getas Pejaten RT 01/RW 02',
            'password' => Hash::make('12345678'), // Password default
            'userType' => 'mustahik',
            'is_pimpinan' => false,
        ]);
        
        $mustahik2 = User::create([
            'name' => 'Siti Aminah',
            'email' => '5555666677778888@baznas.local',
            'nik' => '5555666677778888',
            'phone' => '085222222222',
            'address' => 'Desa Jati Wetan RT 03/RW 01',
            'password' => Hash::make('12345678'),
            'userType' => 'mustahik',
            'is_pimpinan' => false,
        ]);
        
        $mustahik3 = User::create([
            'name' => 'Budi Santoso',
            'email' => '9999888877776666@baznas.local',
            'nik' => '9999888877776666',
            'phone' => '085333333333',
            'address' => 'Desa Nganguk RT 05/RW 04',
            'password' => Hash::make('12345678'),
            'userType' => 'mustahik',
            'is_pimpinan' => false,
        ]);

        // 4. BUAT 15 SURAT MASUK (10 PERMOHONAN, 5 LAINNYA)
        $mustahiks = [$mustahik1, $mustahik2, $mustahik3];
        $perihal_bantuan = ['Permohonan Bantuan Biaya Pendidikan', 'Permohonan Bantuan Kesehatan', 'Permohonan Bantuan Modal Usaha', 'Permohonan Bantuan Konsumtif'];
        $kategori_bantuan = ['pendidikan', 'kesehatan', 'produktif', 'konsumtif'];
        
        for ($i = 1; $i <= 15; $i++) {
            $isPermohonan = ($i <= 10); // Buat 10 permohonan
            $mustahik = $mustahiks[array_rand($mustahiks)];
            $kategori_idx = array_rand($kategori_bantuan);
            $tgl_surat = Carbon::now()->subDays(rand(5, 30));
            $tgl_terima = $tgl_surat->addDays(rand(1, 3));

            $letter = IncomingLetter::create([
                'agenda_number' => sprintf('%03d', $i) . '/BAZNAS-KD/' . $tgl_terima->format('m/Y'),
                'tracking_code' => 'BZN-' . strtoupper(Str::random(6)),
                'sender_name' => $isPermohonan ? $mustahik->name : 'Instansi Ke-' . $i,
                'letter_number' => '123/X/2025',
                'letter_date' => $tgl_surat,
                'received_at' => $tgl_terima,
                'subject' => $isPermohonan ? $perihal_bantuan[$kategori_idx] : 'Surat Undangan Rapat Koordinasi',
                'category' => $isPermohonan ? 'Permohonan Bantuan' : 'Undangan',
                'file_path' => 'public/surat_masuk/dummy.pdf', // Path palsu
                'status' => $isPermohonan ? 'Permohonan Diproses' : 'Diterima Petugas',
                'created_by_user_id' => $staf1->id,
            ]);

            if ($isPermohonan) {
                Request::create([
                    'user_id' => $mustahik->id,
                    'incoming_letter_id' => $letter->id,
                    'status' => 'pending',
                    'requestType' => $kategori_bantuan[$kategori_idx],
                    'amount' => rand(500000, 3000000),
                    'reason' => 'Untuk membantu meringankan biaya ' . $kategori_bantuan[$kategori_idx],
                    'urgency' => 'normal',
                    'familyMembers' => rand(2, 5),
                    'monthlyIncome' => rand(300000, 1500000),
                    'jobStatus' => 'freelance',
                    'description' => 'Keterangan tambahan untuk permohonan.',
                ]);
            }
        }

        // 5. BUAT 3 DISPOSISI
        $surat_untuk_disposisi = IncomingLetter::where('category', 'Permohonan Bantuan')->take(3)->get();
        
        $dispo1 = Disposition::create([
            'incoming_letter_id' => $surat_untuk_disposisi[0]->id,
            'from_user_id' => $pimpinan->id,
            'to_user_id' => $staf1->id,
            'notes' => 'Tolong segera lakukan survei kelayakan ke rumah ybs.',
            'status' => 'Pending',
        ]);
        
        $dispo2 = Disposition::create([
            'incoming_letter_id' => $surat_untuk_disposisi[1]->id,
            'from_user_id' => $pimpinan->id,
            'to_user_id' => $staf1->id,
            'notes' => 'Verifikasi data pendidikan anak.',
            'status' => 'Selesai', // 1 disposisi selesai
            'follow_up_notes' => 'Sudah diverifikasi, data valid.',
        ]);
        
        $dispo3 = Disposition::create([
            'incoming_letter_id' => $surat_untuk_disposisi[2]->id,
            'from_user_id' => $pimpinan->id,
            'to_user_id' => $staf1->id,
            'notes' => 'Cek kondisi usaha.',
            'status' => 'Pending',
        ]);
        
        // 6. BUAT 3 ARSIP INTERNAL
        DocumentArchive::create([
            'title' => 'Daftar Hadir Rapat Koordinasi November 2025',
            'description' => 'Rapat koordinasi internal staf BAZNAS.',
            'file_path' => 'public/arsip_internal/daftar_hadir.pdf',
            'document_date' => Carbon::now()->subDays(5),
            'uploaded_by_user_id' => $staf1->id,
        ]);
        DocumentArchive::create([
            'title' => 'Notulen Rapat Mingguan',
            'description' => 'Hasil rapat mingguan 4 November 2025.',
            'file_path' => 'public/arsip_internal/notulen.pdf',
            'document_date' => Carbon::now()->subDays(4),
            'uploaded_by_user_id' => $staf1->id,
        ]);
    }
}