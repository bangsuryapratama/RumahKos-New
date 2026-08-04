<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            ['name' => 'Kamar Mandi Dalam', 'icon' => 'fas fa-bath'],
            ['name' => 'AC (Air Conditioner)', 'icon' => 'fas fa-snowflake'],
            ['name' => 'WiFi Super Cepat 100Mbps', 'icon' => 'fas fa-wifi'],
            ['name' => 'Kasur Springbed & Bantal', 'icon' => 'fas fa-bed'],
            ['name' => 'Lemari Pakaian 2 Pintu', 'icon' => 'fas fa-warehouse'],
            ['name' => 'Meja & Kursi Kerja', 'icon' => 'fas fa-table'],
            ['name' => 'Smart TV LED 32 Inch', 'icon' => 'fas fa-tv'],
            ['name' => 'Water Heater / Air Hangat', 'icon' => 'fas fa-shower'],
            ['name' => 'Parkir Motor Luas & Aman', 'icon' => 'fas fa-motorcycle'],
            ['name' => 'Parkir Mobil', 'icon' => 'fas fa-car'],
            ['name' => 'Dapur Bersama Lengkap', 'icon' => 'fas fa-utensils'],
            ['name' => 'Keamanan & CCTV 24 Jam', 'icon' => 'fas fa-shield-halved'],
            ['name' => 'Mesin Cuci & Area Laundry', 'icon' => 'fas fa-jug-detergent'],
            ['name' => 'Kulkas Bersama', 'icon' => 'fas fa-cube'],
            ['name' => 'Dispenser Air Minum Gratis', 'icon' => 'fas fa-faucet-drip'],
        ];

        foreach ($facilities as $facility) {
            Facility::updateOrCreate(
                ['name' => $facility['name']],
                ['icon' => $facility['icon']]
            );
        }
    }
}
