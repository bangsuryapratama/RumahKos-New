<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property;
use App\Models\User;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role_id', 1)->first();

        $mapsEmbed = '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.054366914561!2d107.5956743!3d-6.8840748!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e6927a4d53cb%3A0x8e578c77be0e93b6!2sJl.%20Cemara%2C%20Pasteur%2C%20Kec.%20Sukajadi%2C%20Kota%20Bandung!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';

        Property::updateOrCreate(
            ['id' => 1],
            [
                'owner_id'    => $admin ? $admin->id : 1,
                'name'        => 'Cemara Living & Residence',
                'address'     => 'Jl. Cemara No. 45, Hegarmanah, Cidadap, Kota Bandung, Jawa Barat 40141',
                'maps_embed'  => $mapsEmbed,
                'phone'       => '083841806357',
                'whatsapp'    => '083841806357',
                'description' => 'Kost eksklusif nyaman, aman, dan strategis di pusat kota Bandung. Dilengkapi fasilitas lengkap, internet 100Mbps, kamar mandi dalam, keamanan 24 jam & parkir luas.',
            ]
        );
    }
}
