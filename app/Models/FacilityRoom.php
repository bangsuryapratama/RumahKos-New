<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $room_id
 * @property int $facility_id
 * @property-read \App\Models\Facility $facility
 * @property-read \App\Models\Room $room
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FacilityRoom newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FacilityRoom newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FacilityRoom query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FacilityRoom whereFacilityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FacilityRoom whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FacilityRoom whereRoomId($value)
 * @mixin \Eloquent
 */
class FacilityRoom extends Model
{
    use HasFactory;

    protected $table = 'facility_room';

    protected $fillable = [
        'room_id', 'facility_id'
    ];

    public $timestamps = false; // Pivot table biasanya ga pake created_at / updated_at

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}
