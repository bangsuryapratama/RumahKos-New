<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Facility;
use App\Models\Property;
use Database\Seeders\Helpers\ImageSeederHelper;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $property = Property::first();
        $propertyId = $property ? $property->id : 1;

        $allFacilities = Facility::all();

        $roomsData = [
            [
                'name'          => 'Deluxe Room A01',
                'floor'         => 1,
                'size'          => '4x5 m',
                'status'        => 'available',
                'price'         => 2200000,
                'billing_cycle' => 'monthly',
                'image_file'    => 'room_deluxe_1.webp',
                'image_url'     => 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=1000&q=80',
                'title'         => 'Deluxe Room A01',
                'facilities'    => ['Kamar Mandi Dalam', 'AC (Air Conditioner)', 'WiFi Super Cepat 100Mbps', 'Kasur Springbed & Bantal', 'Lemari Pakaian 2 Pintu', 'Meja & Kursi Kerja', 'Smart TV LED 32 Inch', 'Water Heater / Air Hangat', 'Parkir Motor Luas & Aman', 'Keamanan & CCTV 24 Jam'],
            ],
            [
                'name'          => 'Deluxe Room A02',
                'floor'         => 1,
                'size'          => '4x5 m',
                'status'        => 'occupied',
                'price'         => 2200000,
                'billing_cycle' => 'monthly',
                'image_file'    => 'room_deluxe_2.webp',
                'image_url'     => 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?w=1000&q=80',
                'title'         => 'Deluxe Room A02',
                'facilities'    => ['Kamar Mandi Dalam', 'AC (Air Conditioner)', 'WiFi Super Cepat 100Mbps', 'Kasur Springbed & Bantal', 'Lemari Pakaian 2 Pintu', 'Meja & Kursi Kerja', 'Smart TV LED 32 Inch', 'Water Heater / Air Hangat', 'Parkir Motor Luas & Aman', 'Keamanan & CCTV 24 Jam'],
            ],
            [
                'name'          => 'Deluxe Room A03',
                'floor'         => 1,
                'size'          => '4x5 m',
                'status'        => 'available',
                'price'         => 2200000,
                'billing_cycle' => 'monthly',
                'image_file'    => 'room_deluxe_3.webp',
                'image_url'     => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=1000&q=80',
                'title'         => 'Deluxe Room A03',
                'facilities'    => ['Kamar Mandi Dalam', 'AC (Air Conditioner)', 'WiFi Super Cepat 100Mbps', 'Kasur Springbed & Bantal', 'Lemari Pakaian 2 Pintu', 'Meja & Kursi Kerja', 'Smart TV LED 32 Inch', 'Water Heater / Air Hangat', 'Parkir Motor Luas & Aman', 'Keamanan & CCTV 24 Jam'],
            ],
            [
                'name'          => 'Executive Suite B01',
                'floor'         => 2,
                'size'          => '5x6 m',
                'status'        => 'occupied',
                'price'         => 2800000,
                'billing_cycle' => 'monthly',
                'image_file'    => 'room_executive_1.webp',
                'image_url'     => 'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=1000&q=80',
                'title'         => 'Executive Suite B01',
                'facilities'    => ['Kamar Mandi Dalam', 'AC (Air Conditioner)', 'WiFi Super Cepat 100Mbps', 'Kasur Springbed & Bantal', 'Lemari Pakaian 2 Pintu', 'Meja & Kursi Kerja', 'Smart TV LED 32 Inch', 'Water Heater / Air Hangat', 'Parkir Motor Luas & Aman', 'Parkir Mobil', 'Dapur Bersama Lengkap', 'Keamanan & CCTV 24 Jam'],
            ],
            [
                'name'          => 'Executive Suite B02',
                'floor'         => 2,
                'size'          => '5x6 m',
                'status'        => 'available',
                'price'         => 2800000,
                'billing_cycle' => 'monthly',
                'image_file'    => 'room_executive_2.webp',
                'image_url'     => 'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?w=1000&q=80',
                'title'         => 'Executive Suite B02',
                'facilities'    => ['Kamar Mandi Dalam', 'AC (Air Conditioner)', 'WiFi Super Cepat 100Mbps', 'Kasur Springbed & Bantal', 'Lemari Pakaian 2 Pintu', 'Meja & Kursi Kerja', 'Smart TV LED 32 Inch', 'Water Heater / Air Hangat', 'Parkir Motor Luas & Aman', 'Parkir Mobil', 'Dapur Bersama Lengkap', 'Keamanan & CCTV 24 Jam'],
            ],
            [
                'name'          => 'Executive Suite B03',
                'floor'         => 2,
                'size'          => '5x6 m',
                'status'        => 'occupied',
                'price'         => 2800000,
                'billing_cycle' => 'monthly',
                'image_file'    => 'room_executive_3.webp',
                'image_url'     => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=1000&q=80',
                'title'         => 'Executive Suite B03',
                'facilities'    => ['Kamar Mandi Dalam', 'AC (Air Conditioner)', 'WiFi Super Cepat 100Mbps', 'Kasur Springbed & Bantal', 'Lemari Pakaian 2 Pintu', 'Meja & Kursi Kerja', 'Smart TV LED 32 Inch', 'Water Heater / Air Hangat', 'Parkir Motor Luas & Aman', 'Parkir Mobil', 'Dapur Bersama Lengkap', 'Keamanan & CCTV 24 Jam'],
            ],
            [
                'name'          => 'Standard Room C01',
                'floor'         => 2,
                'size'          => '3.5x4 m',
                'status'        => 'occupied',
                'price'         => 1650000,
                'billing_cycle' => 'monthly',
                'image_file'    => 'room_standard_1.webp',
                'image_url'     => 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1000&q=80',
                'title'         => 'Standard Room C01',
                'facilities'    => ['Kamar Mandi Dalam', 'WiFi Super Cepat 100Mbps', 'Kasur Springbed & Bantal', 'Lemari Pakaian 2 Pintu', 'Meja & Kursi Kerja', 'Parkir Motor Luas & Aman', 'Dapur Bersama Lengkap', 'Keamanan & CCTV 24 Jam'],
            ],
            [
                'name'          => 'Standard Room C02',
                'floor'         => 3,
                'size'          => '3.5x4 m',
                'status'        => 'available',
                'price'         => 1650000,
                'billing_cycle' => 'monthly',
                'image_file'    => 'room_standard_2.webp',
                'image_url'     => 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=1000&q=80',
                'title'         => 'Standard Room C02',
                'facilities'    => ['Kamar Mandi Dalam', 'WiFi Super Cepat 100Mbps', 'Kasur Springbed & Bantal', 'Lemari Pakaian 2 Pintu', 'Meja & Kursi Kerja', 'Parkir Motor Luas & Aman', 'Dapur Bersama Lengkap', 'Keamanan & CCTV 24 Jam'],
            ],
            [
                'name'          => 'Standard Room C03',
                'floor'         => 3,
                'size'          => '3.5x4 m',
                'status'        => 'maintenance',
                'price'         => 1650000,
                'billing_cycle' => 'monthly',
                'image_file'    => 'room_standard_3.webp',
                'image_url'     => 'https://images.unsplash.com/photo-1540518614846-7ede433c4550?w=1000&q=80',
                'title'         => 'Standard Room C03',
                'facilities'    => ['Kamar Mandi Dalam', 'WiFi Super Cepat 100Mbps', 'Kasur Springbed & Bantal', 'Lemari Pakaian 2 Pintu', 'Meja & Kursi Kerja', 'Parkir Motor Luas & Aman', 'Dapur Bersama Lengkap', 'Keamanan & CCTV 24 Jam'],
            ],
            [
                'name'          => 'VIP Penthouse D01',
                'floor'         => 3,
                'size'          => '6x7 m',
                'status'        => 'occupied',
                'price'         => 3500000,
                'billing_cycle' => 'monthly',
                'image_file'    => 'room_vip_1.webp',
                'image_url'     => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1000&q=80',
                'title'         => 'VIP Penthouse D01',
                'facilities'    => ['Kamar Mandi Dalam', 'AC (Air Conditioner)', 'WiFi Super Cepat 100Mbps', 'Kasur Springbed & Bantal', 'Lemari Pakaian 2 Pintu', 'Meja & Kursi Kerja', 'Smart TV LED 32 Inch', 'Water Heater / Air Hangat', 'Parkir Motor Luas & Aman', 'Parkir Mobil', 'Dapur Bersama Lengkap', 'Keamanan & CCTV 24 Jam', 'Mesin Cuci & Area Laundry', 'Kulkas Bersama', 'Dispenser Air Minum Gratis'],
            ],
            [
                'name'          => 'VIP Penthouse D02',
                'floor'         => 3,
                'size'          => '6x7 m',
                'status'        => 'available',
                'price'         => 3500000,
                'billing_cycle' => 'monthly',
                'image_file'    => 'room_vip_2.webp',
                'image_url'     => 'https://images.unsplash.com/photo-1600565193348-f74bd3c7ccdf?w=1000&q=80',
                'title'         => 'VIP Penthouse D02',
                'facilities'    => ['Kamar Mandi Dalam', 'AC (Air Conditioner)', 'WiFi Super Cepat 100Mbps', 'Kasur Springbed & Bantal', 'Lemari Pakaian 2 Pintu', 'Meja & Kursi Kerja', 'Smart TV LED 32 Inch', 'Water Heater / Air Hangat', 'Parkir Motor Luas & Aman', 'Parkir Mobil', 'Dapur Bersama Lengkap', 'Keamanan & CCTV 24 Jam', 'Mesin Cuci & Area Laundry', 'Kulkas Bersama', 'Dispenser Air Minum Gratis'],
            ],
            [
                'name'          => 'Single Cozy E01',
                'floor'         => 1,
                'size'          => '3x4 m',
                'status'        => 'available',
                'price'         => 1400000,
                'billing_cycle' => 'monthly',
                'image_file'    => 'room_single_1.webp',
                'image_url'     => 'https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?w=1000&q=80',
                'title'         => 'Single Cozy E01',
                'facilities'    => ['Kamar Mandi Dalam', 'WiFi Super Cepat 100Mbps', 'Kasur Springbed & Bantal', 'Lemari Pakaian 2 Pintu', 'Meja & Kursi Kerja', 'Parkir Motor Luas & Aman', 'Dapur Bersama Lengkap', 'Keamanan & CCTV 24 Jam'],
            ],
        ];

        foreach ($roomsData as $item) {
            $imagePath = ImageSeederHelper::createWebpImage(
                'rooms',
                $item['image_file'],
                $item['image_url'],
                [
                    'title' => $item['title'],
                    'subtitle' => $item['size'] . ' • Lantai ' . $item['floor'] . ' • Rp ' . number_format($item['price'], 0, ',', '.'),
                    'bg_color' => [30, 58, 138],
                    'max_width' => 1000,
                    'max_height' => 650,
                ]
            );

            $room = Room::updateOrCreate(
                ['name' => $item['name']],
                [
                    'property_id'   => $propertyId,
                    'floor'         => $item['floor'],
                    'size'          => $item['size'],
                    'status'        => $item['status'],
                    'price'         => $item['price'],
                    'billing_cycle' => $item['billing_cycle'],
                    'image'         => $imagePath,
                ]
            );

            // Sync facilities
            $facilityIds = $allFacilities
                ->whereIn('name', $item['facilities'])
                ->pluck('id')
                ->toArray();

            $room->facilities()->sync($facilityIds);
        }
    }
}
