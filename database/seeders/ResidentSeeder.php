<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Room;
use App\Models\Resident;
use Carbon\Carbon;

class ResidentSeeder extends Seeder
{
    public function run(): void
    {
        $budi = User::where('email', 'budi@gmail.com')->first();
        $siti = User::where('email', 'siti@gmail.com')->first();
        $ahmad = User::where('email', 'ahmad@gmail.com')->first();
        $rina = User::where('email', 'rina@gmail.com')->first();
        $dimas = User::where('email', 'dimas@gmail.com')->first();
        $dewi = User::where('email', 'dewi@gmail.com')->first();
        $eko = User::where('email', 'eko@gmail.com')->first();

        $roomA02 = Room::where('name', 'Deluxe Room A02')->first();
        $roomB01 = Room::where('name', 'Executive Suite B01')->first();
        $roomB03 = Room::where('name', 'Executive Suite B03')->first();
        $roomC01 = Room::where('name', 'Standard Room C01')->first();
        $roomD01 = Room::where('name', 'VIP Penthouse D01')->first();
        $roomA01 = Room::where('name', 'Deluxe Room A01')->first();
        $roomC02 = Room::where('name', 'Standard Room C02')->first();

        $residentsData = [
            // Active Residents
            [
                'user_id'    => $budi?->id,
                'room_id'    => $roomA02?->id,
                'start_date' => Carbon::now()->subMonths(3)->startOfMonth(),
                'end_date'   => Carbon::now()->addMonths(9)->endOfMonth(),
                'status'     => 'active',
            ],
            [
                'user_id'    => $siti?->id,
                'room_id'    => $roomB01?->id,
                'start_date' => Carbon::now()->subMonths(6)->startOfMonth(),
                'end_date'   => Carbon::now()->addMonths(6)->endOfMonth(),
                'status'     => 'active',
            ],
            [
                'user_id'    => $ahmad?->id,
                'room_id'    => $roomB03?->id,
                'start_date' => Carbon::now()->subMonths(2)->startOfMonth(),
                'end_date'   => Carbon::now()->addMonths(10)->endOfMonth(),
                'status'     => 'active',
            ],
            [
                'user_id'    => $rina?->id,
                'room_id'    => $roomC01?->id,
                'start_date' => Carbon::now()->subMonths(4)->startOfMonth(),
                'end_date'   => Carbon::now()->addMonths(8)->endOfMonth(),
                'status'     => 'active',
            ],
            [
                'user_id'    => $dimas?->id,
                'room_id'    => $roomD01?->id,
                'start_date' => Carbon::now()->subMonths(5)->startOfMonth(),
                'end_date'   => Carbon::now()->addMonths(7)->endOfMonth(),
                'status'     => 'active',
            ],
            // Inactive (Past) Residents
            [
                'user_id'    => $dewi?->id,
                'room_id'    => $roomA01?->id,
                'start_date' => Carbon::now()->subMonths(12)->startOfMonth(),
                'end_date'   => Carbon::now()->subMonths(2)->endOfMonth(),
                'status'     => 'inactive',
            ],
            [
                'user_id'    => $eko?->id,
                'room_id'    => $roomC02?->id,
                'start_date' => Carbon::now()->subMonths(8)->startOfMonth(),
                'end_date'   => Carbon::now()->subMonths(1)->endOfMonth(),
                'status'     => 'inactive',
            ],
        ];

        foreach ($residentsData as $res) {
            if ($res['user_id'] && $res['room_id']) {
                Resident::updateOrCreate(
                    [
                        'user_id' => $res['user_id'],
                        'room_id' => $res['room_id'],
                    ],
                    [
                        'start_date' => $res['start_date'],
                        'end_date'   => $res['end_date'],
                        'status'     => $res['status'],
                    ]
                );
            }
        }
    }
}
