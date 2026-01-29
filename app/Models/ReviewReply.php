<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewReply extends Model
{
    protected $fillable = [
        'review_id',
        'user_id',
        'reply'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship to Review
     */
    public function review()
    {
        return $this->belongsTo(Review::class);
    }

    /**
     * Relationship to User (Admin who replied)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get formatted date
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d M Y');
    }
}
