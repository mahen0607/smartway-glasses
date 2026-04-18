<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sensor_data')) {
            Schema::create('sensor_data', function (Blueprint $table) {
                $table->id();
                $table->string('device_id');
                $table->double('jarak_terdekat');
                $table->double('jarak_min')->nullable();
                $table->double('jarak_maks')->nullable();
                $table->integer('rotasi')->nullable();
                $table->double('suhu')->nullable();
                $table->integer('total_deteksi')->nullable();
                $table->boolean('wifi_connected')->default(true);
                $table->boolean('camera_ok')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sensor_data');
    }
};