<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database with complete, production-grade WebP data.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting RumahKos Comprehensive Database Seeding with WebP Assets...');

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            PropertySeeder::class,
            SocialMediaSeeder::class,
            FacilitySeeder::class,
            RoomSeeder::class,
            ResidentSeeder::class,
            PaymentSeeder::class,
            ReviewSeeder::class,
        ]);

        $this->command->info('✨ Database seeding completed successfully with all lightweight WebP images!');
    }
}
