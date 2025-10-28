<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('drones', function (Blueprint $table) {
            $table->id();
            $table->string('model');
            $table->string('serial_no')->nullable();
            $table->json('specs')->nullable();
            $table->text('description')->nullable();
            $table->decimal('hourly_rate', 12, 2)->default(0);
            $table->decimal('daily_rate', 12, 2)->default(0);
            $table->decimal('deposit', 12, 2)->default(0);
            $table->string('status')->default('available');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drones');
    }
};
