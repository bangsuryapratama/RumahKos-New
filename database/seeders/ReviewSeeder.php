<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\ReviewReply;
use App\Models\User;
use App\Models\Room;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role_id', 1)->first();

        $reviewsData = [
            [
                'room_name' => 'Deluxe Room A02',
                'user_email' => 'budi@gmail.com',
                'rating' => 4.9,
                'comment' => 'Kamar sangat bersih dan wangi saat pertama masuk. WiFi kencang buat WFH & streaming lancar jaya. Fasilitas lengkap banget dan lingkungannya tenang.',
                'category_ratings' => [
                    'cleanliness' => 5,
                    'comfort' => 5,
                    'location' => 5,
                    'service' => 4.8,
                ],
                'reply' => 'Terima kasih banyak Mas Budi atas ulasannya! Senang bisa memberikan kenyamanan selama tinggal di sini.',
            ],
            [
                'room_name' => 'Executive Suite B01',
                'user_email' => 'siti@gmail.com',
                'rating' => 5.0,
                'comment' => 'Sangat puas tinggal di sini! Kamar luas, kasur empuk, kamar mandi dalam dengan water heater berfungsi sangat baik. Parkiran mobil juga aman dan dijaga 24 jam.',
                'category_ratings' => [
                    'cleanliness' => 5,
                    'comfort' => 5,
                    'location' => 5,
                    'service' => 5,
                ],
                'reply' => 'Terima kasih Mbak Siti! Kenyamanan dan keamanan penghuni adalah prioritas utama kami.',
            ],
            [
                'room_name' => 'Executive Suite B03',
                'user_email' => 'ahmad@gmail.com',
                'rating' => 4.8,
                'comment' => 'Kost terbaik di daerah Hegarmanah Bandung. Dekat ke mana-mana, banyak tempat makan di sekitar, dan pengelola sangat responsif jika ada kendala fasilitas.',
                'category_ratings' => [
                    'cleanliness' => 4.8,
                    'comfort' => 5,
                    'location' => 5,
                    'service' => 4.7,
                ],
                'reply' => 'Terima kasih atas kepercayaannya Mas Ahmad! Jangan sungkan kabari kami jika butuh bantuan apapun.',
            ],
            [
                'room_name' => 'Standard Room C01',
                'user_email' => 'rina@gmail.com',
                'rating' => 4.7,
                'comment' => 'Harga terjangkau untuk fasilitas yang didapatkan. Dapur bersama bersih dan peralatan masak lengkap. Lingkungan kost juga ramah & tenang.',
                'category_ratings' => [
                    'cleanliness' => 4.8,
                    'comfort' => 4.7,
                    'location' => 4.9,
                    'service' => 4.8,
                ],
                'reply' => 'Terima kasih banyak Mbak Rina! Semoga selalu betah ya.',
            ],
            [
                'room_name' => 'VIP Penthouse D01',
                'user_email' => 'dimas@gmail.com',
                'rating' => 5.0,
                'comment' => 'Paling rekomen buat yang butuh space besar dan view kota Bandung yang keren. Interior modern, smart TV jernih, dan fasilitas laundry sangat membantu.',
                'category_ratings' => [
                    'cleanliness' => 5,
                    'comfort' => 5,
                    'location' => 5,
                    'service' => 5,
                ],
                'reply' => 'Terima kasih ulasan luar biasanya Mas Dimas! Senang bisa menyuguhkan pengalaman terbaik.',
            ],
        ];

        foreach ($reviewsData as $item) {
            $user = User::where('email', $item['user_email'])->first();
            $room = Room::where('name', $item['room_name'])->first();

            if ($user && $room) {
                $review = Review::updateOrCreate(
                    [
                        'room_id' => $room->id,
                        'user_id' => $user->id,
                    ],
                    [
                        'rating' => $item['rating'],
                        'comment' => $item['comment'],
                        'category_ratings' => $item['category_ratings'],
                    ]
                );

                if (!empty($item['reply']) && $admin) {
                    ReviewReply::updateOrCreate(
                        [
                            'review_id' => $review->id,
                        ],
                        [
                            'user_id' => $admin->id,
                            'reply' => $item['reply'],
                        ]
                    );
                }
            }
        }
    }
}
