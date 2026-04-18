<?php
// database/migrations/2024_01_01_000003_create_sensor_log_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // sensor_data
        if (!Schema::hasTable('sensor_data')) {
            Schema::create('sensor_data', function (Blueprint $table) {
                $table->id();
                $table->float('jarak_terdekat')->nullable();
                $table->float('jarak_min')->nullable();
                $table->float('jarak_maks')->nullable();
                $table->string('arah')->default('Depan');
                $table->string('status')->default('Aman');
                $table->integer('rotasi')->nullable();
                $table->float('suhu')->nullable();
                $table->integer('total_deteksi')->default(0);
                $table->boolean('wifi_connected')->default(true);
                $table->boolean('camera_ok')->default(false);
                $table->timestamps();
            });
        } else {
            Schema::table('sensor_data', function (Blueprint $table) {
                if (!Schema::hasColumn('sensor_data','arah'))
                    $table->string('arah')->default('Depan');
                if (!Schema::hasColumn('sensor_data','status'))
                    $table->string('status')->default('Aman');
            });
        }

        // perjalanan_logs
        if (!Schema::hasTable('perjalanan_logs')) {
            Schema::create('perjalanan_logs', function (Blueprint $table) {
                $table->id();
                $table->string('deskripsi');
                $table->float('jarak')->nullable();
                $table->string('status')->default('Aman');
                $table->string('arah')->default('Depan');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('perjalanan_logs');
    }
};
