<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->string('ktp_photo')->nullable()->after('identity_number');
            $table->string('passport_photo')->nullable()->after('ktp_photo');
            $table->string('sim_photo')->nullable()->after('passport_photo');
            $table->string('other_document')->nullable()->after('sim_photo');
        });
    }

    public function down()
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn(['ktp_photo', 'passport_photo', 'sim_photo', 'other_document']);
        });
    }
};
