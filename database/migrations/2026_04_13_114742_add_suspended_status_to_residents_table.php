<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up()
        {
            DB::statement("ALTER TABLE residents MODIFY COLUMN status ENUM('active', 'inactive', 'cancelled', 'suspended') NOT NULL DEFAULT 'inactive'");
        }

        public function down()
        {
            DB::statement("ALTER TABLE residents MODIFY COLUMN status ENUM('active', 'inactive', 'cancelled') NOT NULL DEFAULT 'inactive'");
        }
};
