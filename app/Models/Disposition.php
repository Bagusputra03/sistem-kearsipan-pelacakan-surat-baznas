<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disposition extends Model
{
    use HasFactory;

    protected $fillable = [
        'incoming_letter_id',
        'from_user_id',
        'to_user_id',
        'notes',
        'status',
        'follow_up_notes', 
        'follow_up_attachment',
    ];

    /**
     * Disposisi ini milik satu surat.
     */
    public function incomingLetter()
    {
        return $this->belongsTo(IncomingLetter::class);
    }

    /**
     * Pimpinan yang mengirim disposisi.
     */
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * Petugas yang menerima disposisi.
     */
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}