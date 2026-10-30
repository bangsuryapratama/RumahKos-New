<?php

use App\Models\Room;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('rooms', 'slug')) {
            Schema::table('rooms', function (Blueprint $table) {
                $table->string('slug')->nullable()->unique()->after('name');
            });
        }

        // Backfill SEO-friendly slugs for all existing rooms (including soft-deleted)
        $rooms = Room::withTrashed()->with('property')->get();
        foreach ($rooms as $room) {
            if (empty($room->slug)) {
                $propPart = $room->property ? ($room->property->name . ' ' . ($room->property->city ?? '')) : '';
                $baseSlug = Str::slug(trim($room->name . ' ' . $propPart));
                if (empty($baseSlug)) {
                    $baseSlug = 'kamar-kos-' . $room->id;
                }

                $slug = $baseSlug;
                $counter = 2;
                while (Room::withTrashed()->where('slug', $slug)->where('id', '!=', $room->id)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                $room->slug = $slug;
                $room->saveQuietly();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('rooms', 'slug')) {
            Schema::table('rooms', function (Blueprint $table) {
                $table->dropUnique(['slug']);
                $table->dropColumn('slug');
            });
        }
    }
};
