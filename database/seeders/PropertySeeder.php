<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        Property::create([
            'owner_id'   => 1,
            'name'       => 'Cemara Housing',
            'address'    => 'Jl. Cemara No. 1 Kota Bandung`',
            'maps_embed' => null,
            'phone'      => '083841806357',
            'whatsapp'   => '083841806357',
            'description'=> 'Kost nyaman, bersih, dan strategis',
        ]);
    }
}
