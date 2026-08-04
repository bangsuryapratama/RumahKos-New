<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SocialMedia;

class SocialMediaSeeder extends Seeder
{
    public function run(): void
    {
        SocialMedia::updateOrCreate(
            ['id' => 1],
            [
                'instagram' => 'rumahkos.id',
                'facebook' => 'rumahkos.residence',
                'tiktok' => 'rumahkos_bandung',
            ]
        );
    }
}
