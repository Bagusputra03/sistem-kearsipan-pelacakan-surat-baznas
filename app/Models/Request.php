<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'incoming_letter_id', // <-- PASTIKAN INI DITAMBAHKAN
        'status',
        'requestType',
        'amount',
        'reason',
        'urgency',
        'familyMembers',
        'monthlyIncome',
        'jobStatus',
        'description',
        'reviewedAt',
        'reviewComments',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'reviewedAt' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke model User (Pemohon).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke surat masuk fisiknya (BARU).
     */
    public function incomingLetter()
    {
        return $this->belongsTo(IncomingLetter::class);
    }
}