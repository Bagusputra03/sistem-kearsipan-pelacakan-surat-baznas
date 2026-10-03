<?php

// BAGUS PUTRA SULUNG - 202253111

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Livewire\UserManagement;
use App\Livewire\IncomingLetterManager;
use App\Livewire\MyDispositions;
use App\Livewire\DocumentArchiveManager;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\Auth\MustahikLoginController;
use App\Http\Controllers\StorageAccessController;
use Illuminate\Support\Facades\Route;

Route::get('/login-mustahik', [MustahikLoginController::class, 'create'])->name('mustahik.login');
Route::post('/login-mustahik', [MustahikLoginController::class, 'store'])->name('mustahik.login.store');

Route::post('/lacak-surat', [TrackingController::class, 'track'])->name('tracking.submit');
Route::get('/lacak/hasil/{tracking_code}', [TrackingController::class, 'show'])->name('tracking.result');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute ini akan menangani semua permintaan file storage kita
    Route::get('/storage-file/{path}', [StorageAccessController::class, 'show'])
         ->name('storage.show')
         ->where('path', '.*'); // Izinkan path berisi '/'

    Route::middleware('can:accessOfficerFeatures')->group(function () {
            
        Route::get('/reports/requests', [ReportController::class, 'requestReport'])->name('reports.requests');

        Route::get('/surat-masuk', IncomingLetterManager::class)->name('letters.index');

        Route::get('/arsip-internal', DocumentArchiveManager::class)->name('archives.index'); 

        Route::middleware('can:isStaf')->group(function () {
            Route::get('/users', UserManagement::class)->name('users.index');
            Route::get('/tugas-disposisi', MyDispositions::class)->name('dispositions.my'); 
        });

    });
});

require __DIR__.'/auth.php';
