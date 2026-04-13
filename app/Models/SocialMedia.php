<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    // protected $table = 'socialmedia'; 

    protected $fillable = [
        'instagram',
        'facebook',
        'tiktok',
    ];
}