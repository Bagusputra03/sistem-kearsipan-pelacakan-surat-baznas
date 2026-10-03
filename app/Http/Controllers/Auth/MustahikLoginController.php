<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class MustahikLoginController extends Controller
{
    /**
     * Menampilkan view login Mustahik.
     */
    public function create()
    {
        return view('auth.mustahik-login');
    }

    /**
     * Menangani percobaan login Mustahik.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|digits:16',
            'password' => 'required|string',
        ]);

        // 1. Cek dulu apakah NIK ada dan merupakan Mustahik
        $user = User::where('nik', $request->nik)
                    ->where('userType', 'mustahik')
                    ->first();

        // 2. Jika user (NIK) tidak ditemukan
        if (! $user) {
            throw ValidationException::withMessages([
                'nik' => 'NIK yang Anda masukkan tidak terdaftar.',
            ]);
        }

        // 3. Jika NIK ada, cek password-nya
        if (! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => 'Password yang Anda masukkan salah.',
            ]);
        }

        // 4. Jika NIK dan Password benar, login
        Auth::login($user);
        
        $request->session()->regenerate();
        
        return redirect()->intended('/dashboard');
    }
}