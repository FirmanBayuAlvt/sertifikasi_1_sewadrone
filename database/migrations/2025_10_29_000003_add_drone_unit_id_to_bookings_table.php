<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // tambahkan drone_unit_id; biarkan nullable dulu agar migrasi tidak break
            $table->foreignId('drone_unit_id')->nullable()->after('drone_id')->constrained('drone_units')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('drone_unit_id');
        });
    }
};
