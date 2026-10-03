<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        // Tambahkan kolom baru di sini
        'phone',
        'address',
        'nik',
        'userType',
        'is_pimpinan',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_pimpinan' => 'boolean',
    ];

    /**
     * Surat-surat yang diinput oleh petugas ini.
     */
    public function createdLetters()
    {
        return $this->hasMany(IncomingLetter::class, 'created_by_user_id');
    }

    /**
     * Disposisi yang dikirim oleh pimpinan ini.
     */
    public function sentDispositions()
    {
        return $this->hasMany(Disposition::class, 'from_user_id');
    }

    /**
     * Tugas disposisi yang diterima oleh petugas ini.
     */
    public function receivedDispositions()
    {
        return $this->hasMany(Disposition::class, 'to_user_id');
    }
}
