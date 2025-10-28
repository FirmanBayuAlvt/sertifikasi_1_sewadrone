<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drone_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->text('issue')->nullable();
            $table->text('action')->nullable();
            $table->decimal('cost', 12, 2)->default(0);
            $table->foreignId('technician_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
    }
};
