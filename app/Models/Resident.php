<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $room_id
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \App\Models\Room $room
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resident newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resident newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resident query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resident whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resident whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resident whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resident whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resident whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resident whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resident whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resident whereUserId($value)
 * @mixin \Eloquent
 */
class Resident extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Relasi dengan User (Penghuni)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi dengan Room
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Relasi dengan Payments
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Helper: Hitung durasi tinggal dalam bulan
     */
    public function getDurationInMonths()
    {
        if (!$this->start_date) return 0;
        
        $end = $this->end_date ?? now();
        return $this->start_date->diffInMonths($end);
    }

    /**
     * Helper: Cek apakah kontrak masih aktif
     */
    public function isContractActive()
    {
        if ($this->status !== 'active') return false;
        if (!$this->end_date) return true;
        
        return now()->lte($this->end_date);
    }
}
