<?php
// database/migrations/2024_01_01_000002_create_device_and_gps_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Buat hanya jika belum ada
        if (!Schema::hasTable('device_statuses')) {
            Schema::create('device_statuses', function (Blueprint $table) {
                $table->id();
                $table->string('wifi')->default('Connected');
                $table->string('camera')->default('Error');
                $table->unsignedTinyInteger('battery_pct')->default(50);
                $table->unsignedTinyInteger('battery_hours')->default(4);
                $table->boolean('gps_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('gps_locations')) {
            Schema::create('gps_locations', function (Blueprint $table) {
                $table->id();
                $table->decimal('latitude',  10, 7)->default(-7.9525);
                $table->decimal('longitude', 10, 7)->default(112.6144);
                $table->unsignedSmallInteger('akurasi')->default(3);
                $table->string('alamat')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('device_statuses');
        Schema::dropIfExists('gps_locations');
    }
};
