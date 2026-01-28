<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            [
                'name' => 'Kamar Mandi Dalam',
                'icon' => 'fas fa-bath',
            ],
            [
                'name' => 'AC',
                'icon' => 'fas fa-snowflake',
            ],
            [
                'name' => 'WiFi',
                'icon' => 'fas fa-wifi',
            ],
            [
                'name' => 'Kasur',
                'icon' => 'fas fa-bed',
            ],
            [
                'name' => 'Lemari',
                'icon' => 'fas fa-warehouse',
            ],
            [
                'name' => 'Meja Belajar',
                'icon' => 'fas fa-table',
            ],
            [
                'name' => 'TV',
                'icon' => 'fas fa-tv',
            ],
            [
                'name' => 'Parkir Motor',
                'icon' => 'fas fa-motorcycle',
            ],
            [
                'name' => 'Parkir Mobil',
                'icon' => 'fas fa-car',
            ],
            [
                'name' => 'Dapur Bersama',
                'icon' => 'fas fa-utensils',
            ],
        ];

        foreach ($facilities as $facility) {
            Facility::firstOrCreate(
                ['name' => $facility['name']],
                ['icon' => $facility['icon']]
            );
        }
    }
}
