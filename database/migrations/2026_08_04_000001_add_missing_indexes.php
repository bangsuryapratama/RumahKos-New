<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->index('status');
            $table->index('floor');
        });

        Schema::table('residents', function (Blueprint $table) {
            $table->index('status');
            $table->index(['user_id', 'status']);
            $table->index(['room_id', 'status']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->index('due_date');
            $table->index('billing_month');
            $table->index('paid_at');
            $table->index(['status', 'due_date']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->index(['room_id', 'user_id']);
            $table->index('rating');
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['floor']);
        });

        Schema::table('residents', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['room_id', 'status']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['due_date']);
            $table->dropIndex(['billing_month']);
            $table->dropIndex(['paid_at']);
            $table->dropIndex(['status', 'due_date']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['room_id', 'user_id']);
            $table->dropIndex(['rating']);
        });
    }
};
