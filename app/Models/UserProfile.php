<?php
// app/Models/UserProfile.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $table = 'user_profiles'; // Sesuai table yang udah ada

    protected $fillable = [
        'user_id',
        'phone',
        'identity_number',
        'address',
        'date_of_birth',
        'gender',
        'occupation',
        'emergency_contact',
        'emergency_contact_name',
        'ktp_photo',
        'sim_photo',
        'passport_photo',
        'other_document',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if profile is complete
     */
    public function isComplete()
    {
        return $this->phone
            && $this->identity_number
            && $this->ktp_photo;
    }

    /**
     * Get completion percentage
     */
    public function getCompletionPercentage()
    {
        $fields = [
            'phone',
            'identity_number',
            'address',
            'date_of_birth',
            'gender',
            'occupation',
            'emergency_contact',
            'ktp_photo'
        ];

        $filled = 0;
        foreach ($fields as $field) {
            if ($this->$field) {
                $filled++;
            }
        }

        return round(($filled / count($fields)) * 100);
    }
}
