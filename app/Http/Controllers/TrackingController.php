<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncomingLetter;

class TrackingController extends Controller
{
    /**
     * Menangani form submit pelacakan dari halaman utama.
     */
    public function track(Request $request)
    {
        // Validasi input
        $request->validate([
            'tracking_code' => 'required|string|max:20',
        ]);

        $code = $request->input('tracking_code');
        
        // Cari surat berdasarkan kode
        $letter = IncomingLetter::where('tracking_code', $code)->first();

        if ($letter) {
            // Jika ketemu, redirect ke halaman hasil yang aman (GET)
            return redirect()->route('tracking.result', ['tracking_code' => $code]);
        } else {
            // Jika tidak ketemu, kembalikan ke halaman sebelumnya dengan error
            return back()->with('error', 'Kode Tracking tidak ditemukan. Pastikan kode sudah benar.');
        }
    }

    /**
     * Menampilkan halaman hasil pelacakan.
     * (Versi ini sudah diperbarui dengan logika disposisi)
     */
    public function show($tracking_code)
    {
        // 1. Ambil surat, DAN semua relasi yang kita perlukan
        $letter = IncomingLetter::with([
                            'requestData', // Data permohonan
                            'dispositions.fromUser', // Riwayat disposisi (dari siapa)
                            'dispositions.toUser' // Riwayat disposisi (ke siapa)
                        ])
                        ->where('tracking_code', $tracking_code)
                        ->firstOrFail();
        
        $steps = [];
        $currentStatus = $letter->status;
        $isCompleted = false;

        // Step 1: Selalu "Diterima Petugas"
        $steps[] = [
            'name' => 'Diterima Petugas',
            'status' => 'completed',
            'time' => $letter->created_at
        ];

        // Step 2: "Permohonan Diproses" (Jika ini surat permohonan)
        if ($letter->requestData) {
            $steps[] = [
                'name' => 'Permohonan Diproses',
                'status' => 'completed',
                'time' => $letter->requestData->created_at // Ambil waktu permohonan dibuat
            ];
        }
        
        // Step 3 & 4: Tampilkan alur Disposisi dan Tindak Lanjut
        // Loop setiap riwayat disposisi yang ada
        foreach ($letter->dispositions->sortBy('created_at') as $disposition) {
            
            // Tampilkan langkah disposisi
            $steps[] = [
                'name' => 'Didisposisikan ke ' . ($disposition->toUser->name ?? 'Tim Internal'),
                'status' => 'completed',
                'time' => $disposition->created_at
            ];
            
            // Tampilkan langkah tindak lanjut JIKA sudah selesai
            if ($disposition->status == 'Selesai') {
                 $steps[] = [
                    'name' => 'Tindak Lanjut Selesai (oleh ' . ($disposition->toUser->name ?? 'Staf') . ')',
                    'status' => 'completed',
                    'time' => $disposition->updated_at
                ];
            }
        }

        // Step 5: Tampilkan Status Terakhir (Final)
        if ($currentStatus == 'Disetujui') {
            $steps[] = [
                'name' => 'Disetujui', 
                'status' => 'completed', 
                'time' => $letter->requestData->reviewedAt ?? $letter->updated_at
            ];
            $isCompleted = true;
        } elseif ($currentStatus == 'Ditolak') {
            $steps[] = [
                'name' => 'Ditolak', 
                'status' => 'completed_error', 
                'time' => $letter->requestData->reviewedAt ?? $letter->updated_at
            ];
            $isCompleted = true;
        } elseif (str_starts_with($currentStatus, 'Didisposisikan')) {
            // Jika status terakhir adalah disposisi (dan belum selesai), tandai sebagai 'current'
            $steps[] = [
                'name' => $currentStatus . ' (Menunggu tindak lanjut)',
                'status' => 'current',
                'time' => $letter->updated_at
            ];
        }

        return view('tracking.result', compact('letter', 'steps', 'isCompleted'));
    }
}