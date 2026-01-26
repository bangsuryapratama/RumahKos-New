<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->string('name');
            $table->integer('floor')->nullable();
            $table->string('size')->nullable();
            $table->enum('status', ['available', 'occupied', 'maintenance'])->default('available');
            $table->bigInteger('price')->default(0);
            $table->enum('billing_cycle', ['daily','weekly','monthly','yearly'])->default('monthly');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rooms');
    }
};
