<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Routing\Controller as BaseController;

/**
 * Catatan: Kita extends BaseController BUKAN Controller
 * untuk menghindari masalah autoloader dengan file Controller.php lokal Anda.
 */
class StorageAccessController extends BaseController //
{
    public function show($path)
    {
        // 1. Pastikan hanya pengguna yang login yang bisa mengakses
        if (!auth()->check()) {
            abort(403, 'Unauthorized');
        }
        
        // 2. Mencegah upaya keluar dari direktori (Directory Traversal)
        if (Str::contains($path, '..')) {
            abort(403, 'Invalid path');
        }

        // 3. Cek apakah file ada di disk 'local'
        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'File not found');
        }

        // 4. Ambil file dan kirimkan ke browser sebagai response
        return Storage::disk('local')->response($path);
    }
}