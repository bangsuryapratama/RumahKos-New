<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property int $user_id
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $identity_number
 * @property \Illuminate\Support\Carbon|null $date_of_birth
 * @property string|null $occupation
 * @property string|null $emergency_contact
 * @property string|null $emergency_contact_name
 * @property string|null $gender
 * @property string|null $ktp_photo
 * @property string|null $passport_photo
 * @property string|null $sim_photo
 * @property string|null $other_document
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 */
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
        'ktp_photo',
        'passport_photo',
        'sim_photo',
        'other_document',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper untuk mendapatkan URL dokumen
    public function getKtpPhotoUrlAttribute()
    {
        return $this->ktp_photo ? Storage::url($this->ktp_photo) : null;
    }

    public function getPassportPhotoUrlAttribute()
    {
        return $this->passport_photo ? Storage::url($this->passport_photo) : null;
    }

    public function getSimPhotoUrlAttribute()
    {
        return $this->sim_photo ? Storage::url($this->sim_photo) : null;
    }

    public function getOtherDocumentUrlAttribute()
    {
        return $this->other_document ? Storage::url($this->other_document) : null;
    }

    // Helper untuk menghapus file lama
    public function deleteOldDocument($field)
    {
        if ($this->$field && Storage::exists($this->$field)) {
            Storage::delete($this->$field);
        }
    }
}
