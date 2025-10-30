<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('drone_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drone_id')->constrained('drones')->onDelete('cascade');
            $table->string('code')->unique(); // kode unit unik
            $table->string('name')->nullable();
            $table->enum('status', ['available','booked','maintenance'])->default('available');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('drone_units');
    }
};
