<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
    {
        Schema::table('device_statuses', function (Blueprint $table) {
            if (!Schema::hasColumn('device_statuses', 'battery_pct')) {
                $table->integer('battery_pct')->default(100)->after('camera');
            }
            if (!Schema::hasColumn('device_statuses', 'battery_hours')) {
                $table->float('battery_hours')->nullable()->after('battery_pct');
            }
        });
    }

    public function down(): void
    {
        Schema::table('device_statuses', function (Blueprint $table) {
            $table->dropColumn(['battery_pct', 'battery_hours']);
        });
    }
};
