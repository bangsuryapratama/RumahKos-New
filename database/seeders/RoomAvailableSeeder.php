<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use Carbon\Carbon;

class RoomAvailableSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 9; $i++) {
            Room::create([
                'property_id'   => 1,
                'name'          => 'CEMARA ' . $i,
                'floor'         => 1,
                'size'          => '5x6',
                'status'        => 'available',
                'price'         => 2000000,
                'billing_cycle' => 'monthly',
                'image'         => 'rooms/default_room.jpg',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }
    }
}
