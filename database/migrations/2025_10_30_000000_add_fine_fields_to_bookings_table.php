<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            // jumlah hari total peminjaman (integer)
            $table->integer('duration_days')->nullable()->after('duration_hours');

            // lama melebihi limit (0 jika tidak telat)
            $table->integer('late_days')->default(0)->after('duration_days');

            // denda per hari yang dikenakan (decimal, disimpan saat booking dibuat)
            $table->decimal('fine_per_day', 12, 2)->nullable()->after('late_days');

            // total denda untuk booking ini
            $table->decimal('late_fee', 12, 2)->default(0)->after('fine_per_day');
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['duration_days', 'late_days', 'fine_per_day', 'late_fee']);
        });
    }
};
