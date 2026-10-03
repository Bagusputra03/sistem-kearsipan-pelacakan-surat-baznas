<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingLetter extends Model
{
    use HasFactory;

    protected $fillable = [
        'agenda_number',
        'tracking_code',
        'sender_name',
        'sender_address',
        'letter_number',
        'letter_date',
        'received_at',
        'subject',
        'trait',
        'category',
        'file_path',
        'status',
        'created_by_user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'letter_date' => 'datetime',
        'received_at' => 'datetime',
    ];

    /**
     * Petugas yang menginput surat ini.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Surat ini memiliki banyak riwayat disposisi.
     */
    public function dispositions()
    {
        return $this->hasMany(Disposition::class);
    }

    /**
     * Surat ini (jika kategori permohonan) akan menghasilkan satu data Request.
     */
    public function requestData()
    {
        return $this->hasOne(Request::class);
    }
}