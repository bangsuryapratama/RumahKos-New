<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'room_id',
        'user_id',
        'rating',
        'comment',
        'category_ratings'
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'category_ratings' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship to Room
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Relationship to User (Tenant)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship to Replies (Admin responses)
     */
    public function replies()
    {
        return $this->hasMany(ReviewReply::class);
    }

    /**
     * Get the latest reply (for display)
     */
    public function latestReply()
    {
        return $this->hasOne(ReviewReply::class)->latestOfMany();
    }

    /**
     * Get formatted date
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d M Y');
    }

    /**
     * Get time ago format (1 bulan yang lalu)
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get category score by name
     */
    public function getCategoryScore($category)
    {
        if (!$this->category_ratings || !isset($this->category_ratings[$category])) {
            return null;
        }

        return $this->category_ratings[$category];
    }

    /**
     * Check if review has category ratings
     */
    public function hasCategoryRatings()
    {
        return !empty($this->category_ratings);
    }

    /**
     * Check if user can edit this review
     */
    public function canEdit($userId)
    {
        return $this->user_id == $userId;
    }

    /**
     * Check if review has been replied by admin
     */
    public function hasReply()
    {
        return $this->replies()->exists();
    }
}
