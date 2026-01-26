<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $property_id
 * @property string $name
 * @property int|null $floor
 * @property string|null $size
 * @property string $status
 * @property int $price
 * @property string $billing_cycle
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Resident|null $currentResident
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Facility> $facilities
 * @property-read int|null $facilities_count
 * @property-read \App\Models\Property $property
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Resident> $residents
 * @property-read int|null $residents_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereBillingCycle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereFloor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'name',
        'floor',
        'size',
        'status',
        'price',
        'billing_cycle',
        'image',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }


    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'facility_room');
    }

    public function residents()
    {
        return $this->hasMany(Resident::class);
    }

    public function currentResident()
    {
        return $this->hasOne(Resident::class)->where('status', 'active');
    }
}