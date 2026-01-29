<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'identity_number',
        'date_of_birth',
        'occupation',
        'emergency_contact',
        'emergency_contact_name',
        'gender',

        // PENTING: Kolom dokumen harus ada di sini!
        'ktp_photo',
        'sim_photo',
        'passport_photo',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Relationship dengan User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Delete old document helper
     */
    public function deleteOldDocument($field)
    {
        if ($this->$field && Storage::disk('public')->exists($this->$field)) {
            Storage::disk('public')->delete($this->$field);
        }
    }

    /**
     * Get full URL untuk KTP
     */
    public function getKtpPhotoUrlAttribute()
    {
        return $this->ktp_photo ? asset('storage/' . $this->ktp_photo) : null;
    }

    /**
     * Get full URL untuk SIM
     */
    public function getSimPhotoUrlAttribute()
    {
        return $this->sim_photo ? asset('storage/' . $this->sim_photo) : null;
    }

    /**
     * Get full URL untuk Passport
     */
    public function getPassportPhotoUrlAttribute()
    {
        return $this->passport_photo ? asset('storage/' . $this->passport_photo) : null;
    }
}
