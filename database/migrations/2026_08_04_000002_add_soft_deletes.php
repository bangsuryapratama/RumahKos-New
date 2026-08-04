<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('residents', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('properties', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        $tables = ['users', 'residents', 'payments', 'rooms', 'properties'];
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropSoftDeletes();
            });
        }
    }
};
