<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Migration untuk menambahkan kolom social login di tabel users
     */
    public function up(): void
    {
        // Tambahkan kolom untuk social login di tabel users yang sudah ada
        Schema::table('users', function (Blueprint $table) {
            $table->string('provider')->nullable()->after('remember_token'); // google, facebook
            $table->string('provider_id')->nullable()->after('provider');
            $table->string('avatar')->nullable()->after('provider_id');
        });

        // Tambahkan kolom tambahan di user_profiles untuk data lengkap penghuni
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable()->after('identity_number');
            $table->string('occupation')->nullable()->after('date_of_birth'); // mahasiswa/pekerja
            $table->string('emergency_contact')->nullable()->after('occupation');
            $table->string('emergency_contact_name')->nullable()->after('emergency_contact');
            $table->enum('gender', ['male', 'female'])->nullable()->after('emergency_contact_name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['provider', 'provider_id', 'avatar']);
        });

        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth', 
                'occupation', 
                'emergency_contact',
                'emergency_contact_name',
                'gender'
            ]);
        });
    }
};