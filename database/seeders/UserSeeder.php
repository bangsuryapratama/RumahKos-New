<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserProfile;
use Database\Seeders\Helpers\ImageSeederHelper;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin User
        $adminAvatar = ImageSeederHelper::createWebpImage(
            'avatars',
            'admin.webp',
            'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400&q=80',
            [
                'title' => 'Admin Kos',
                'subtitle' => 'Administrator',
                'bg_color' => [15, 23, 42],
                'max_width' => 400,
                'max_height' => 400,
            ]
        );

        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin RumahKos',
                'password' => Hash::make('12345678'),
                'role_id' => 1,
                'avatar' => $adminAvatar,
            ]
        );

        UserProfile::updateOrCreate(
            ['user_id' => $admin->id],
            [
                'phone' => '083841806357',
                'address' => 'Jl. Cemara No. 45, Bandung',
                'identity_number' => '3273010101900001',
            ]
        );

        // 2. Tenants (Penghuni)
        $tenants = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@gmail.com',
                'phone' => '081234567801',
                'address' => 'Jl. Sukajadi No. 12, Bandung',
                'identity' => '3273010101980001',
                'image_url' => 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=400&q=80',
                'file' => 'user_budi.webp',
            ],
            [
                'name' => 'Siti Rahmawati',
                'email' => 'siti@gmail.com',
                'phone' => '081234567802',
                'address' => 'Jl. Setiabudi No. 88, Bandung',
                'identity' => '3273010101990002',
                'image_url' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400&q=80',
                'file' => 'user_siti.webp',
            ],
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad@gmail.com',
                'phone' => '081234567803',
                'address' => 'Jl. Dipatiukur No. 24, Bandung',
                'identity' => '3273010101970003',
                'image_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&q=80',
                'file' => 'user_ahmad.webp',
            ],
            [
                'name' => 'Rina Anggraini',
                'email' => 'rina@gmail.com',
                'phone' => '081234567804',
                'address' => 'Jl. Cihampelas No. 105, Bandung',
                'identity' => '3273010101990004',
                'image_url' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=400&q=80',
                'file' => 'user_rina.webp',
            ],
            [
                'name' => 'Dimas Pratama',
                'email' => 'dimas@gmail.com',
                'phone' => '081234567805',
                'address' => 'Jl. Dago No. 150, Bandung',
                'identity' => '3273010101960005',
                'image_url' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&q=80',
                'file' => 'user_dimas.webp',
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi@gmail.com',
                'phone' => '081234567806',
                'address' => 'Jl. Riau No. 42, Bandung',
                'identity' => '3273010101980006',
                'image_url' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=400&q=80',
                'file' => 'user_dewi.webp',
            ],
            [
                'name' => 'Eko Prasetyo',
                'email' => 'eko@gmail.com',
                'phone' => '081234567807',
                'address' => 'Jl. Buah Batu No. 77, Bandung',
                'identity' => '3273010101970007',
                'image_url' => 'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?w=400&q=80',
                'file' => 'user_eko.webp',
            ],
            [
                'name' => 'Putri Maharani',
                'email' => 'putri@gmail.com',
                'phone' => '081234567808',
                'address' => 'Jl. Pasteur No. 33, Bandung',
                'identity' => '3273010101990008',
                'image_url' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=400&q=80',
                'file' => 'user_putri.webp',
            ],
        ];

        foreach ($tenants as $idx => $t) {
            $avatarPath = ImageSeederHelper::createWebpImage(
                'avatars',
                $t['file'],
                $t['image_url'],
                [
                    'title' => $t['name'],
                    'subtitle' => 'Penghuni Kos',
                    'bg_color' => [37, 99, 235],
                    'max_width' => 400,
                    'max_height' => 400,
                ]
            );

            $user = User::updateOrCreate(
                ['email' => $t['email']],
                [
                    'name' => $t['name'],
                    'password' => Hash::make('12345678'),
                    'role_id' => 2,
                    'avatar' => $avatarPath,
                ]
            );

            UserProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'phone' => $t['phone'],
                    'address' => $t['address'],
                    'identity_number' => $t['identity'],
                ]
            );
        }
    }
}
