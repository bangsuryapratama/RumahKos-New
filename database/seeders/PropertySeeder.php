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
        $adminId = $admin ? $admin->id : 1;

        $properties = [
            [
                'id'          => 1,
                'owner_id'    => $adminId,
                'name'        => 'Cemara Living & Residence',
                'city'        => 'Bandung',
                'type'        => 'campur',
                'address'     => 'Jl. Cemara No. 45, Sukajadi, Kota Bandung, Jawa Barat 40141',
                'maps_embed'  => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.054366914561!2d107.5956743!3d-6.8840748!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e6927a4d53cb%3A0x8e578c77be0e93b6!2sJl.%20Cemara%2C%20Pasteur%2C%20Kec.%20Sukajadi%2C%20Kota%20Bandung!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
                'phone'       => '083841806357',
                'whatsapp'    => '083841806357',
                'description' => 'Kost eksklusif nyaman, aman, dan strategis dekat kampus & pusat kota Bandung. Dilengkapi fasilitas lengkap, internet 100Mbps, kamar mandi dalam, keamanan 24 jam & parkir luas.',
            ],
            [
                'id'          => 2,
                'owner_id'    => $adminId,
                'name'        => 'Senopati Urban Co-Living',
                'city'        => 'Jakarta',
                'type'        => 'putri',
                'address'     => 'Jl. Senopati No. 18, Kebayoran Baru, Jakarta Selatan 12190',
                'maps_embed'  => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.282928574163!2d106.808027!3d-6.226372!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f1505373a6a9%3A0xb3e162f4e427ca96!2sSenopati%2C%20Kby.%20Baru%2C%20Kota%20Jakarta%20Selatan!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
                'phone'       => '081298765432',
                'whatsapp'    => '081298765432',
                'description' => 'Kost putri modern & co-living premium di kawasan SCBD/Senopati. Akses MRT Senayan, kamar full furnished dengan AC, water heater, dapur modern & rooftop lounge.',
            ],
            [
                'id'          => 3,
                'owner_id'    => $adminId,
                'name'        => 'Kalyana Paviliun UGM',
                'city'        => 'Yogyakarta',
                'type'        => 'putra',
                'address'     => 'Jl. Kaliurang KM 5.2, Caturtunggal, Depok, Sleman, DI Yogyakarta 55281',
                'maps_embed'  => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.181829377484!2d110.378942!3d-7.770519!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59b2d978a3f9%3A0x6b245e3f4ef342f2!2sUniversitas%20Gadjah%20Mada!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
                'phone'       => '081323456789',
                'whatsapp'    => '081323456789',
                'description' => 'Kost putra eksklusif 3 menit dari kampus UGM & UNY. Lingkungan tenang, meja kerja ergonomis, WiFi kencang, parkir mobil luas & area komunal estetik.',
            ],
            [
                'id'          => 4,
                'owner_id'    => $adminId,
                'name'        => 'Gubeng Executive Stay',
                'city'        => 'Surabaya',
                'type'        => 'campur',
                'address'     => 'Jl. Raya Gubeng No. 62, Gubeng, Kota Surabaya, Jawa Timur 60281',
                'maps_embed'  => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.702957813247!2d112.748374!3d-7.274641!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fbf665555555%3A0x8cf61e3895e6f658!2sGubeng%2C%20Surabaya!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
                'phone'       => '082134567890',
                'whatsapp'    => '082134567890',
                'description' => 'Kost eksekutif di jantung kota Surabaya. Dekat Stasiun Gubeng, RS Siloam & kampus Unair. Keamanan akses fingerprint/smart door lock & housekeeping mingguan.',
            ],
        ];

        foreach ($properties as $data) {
            Property::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
        }
    }
}
