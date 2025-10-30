<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('drone_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drone_id')->constrained('drones')->cascadeOnDelete();
            $table->string('code')->unique();           // Kode unit -> harus unik
            $table->string('name')->nullable();         // Nama alternatif / label unit (mis: Unit A)
            $table->enum('status', ['available', 'booked', 'maintenance', 'retired'])->default('available');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drone_units');
    }
};
