<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'returned_at')) {
                $table->timestamp('returned_at')->nullable()->after('status');
            }
            if (! Schema::hasColumn('bookings', 'fine_amount')) {
                $table->decimal('fine_amount', 10, 2)->nullable()->after('returned_at');
            }
            if (! Schema::hasColumn('bookings', 'processed_by')) {
                $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete()->after('fine_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('processed_by');
            $table->dropColumn(['fine_amount', 'returned_at']);
        });
    }
};
