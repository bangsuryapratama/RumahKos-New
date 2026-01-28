<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Ubah ke users
            $table->tinyInteger('rating')->unsigned(); // 1-5
            $table->text('comment');
            $table->timestamps();

            $table->unique(['room_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};
