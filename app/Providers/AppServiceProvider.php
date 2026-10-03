<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    // ...

    public function boot(): void
    {
        // 1. Gate untuk SEMUA pengguna internal (Petugas + Pimpinan)
        Gate::define('accessOfficerFeatures', function (User $user) {
            return $user->userType === 'officer';
        });

        // 2. Gate untuk HANYA Pimpinan
        Gate::define('isPimpinan', function (User $user) {
            return $user->userType === 'officer' && $user->is_pimpinan;
        });

        // 3. Gate untuk HANYA Staf (Petugas non-Pimpinan)
        Gate::define('isStaf', function (User $user) {
            return $user->userType === 'officer' && !$user->is_pimpinan;
        });
    }
}