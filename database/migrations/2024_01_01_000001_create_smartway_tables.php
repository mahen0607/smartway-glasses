<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sensor_data', function (Blueprint $table) {
            $table->id();
            $table->float('jarak_terdekat')->nullable();
            $table->float('jarak_min')->nullable();
            $table->float('jarak_maks')->nullable();
            $table->integer('rotasi')->nullable();
            $table->float('suhu')->nullable();
            $table->integer('total_deteksi')->default(0);
            $table->boolean('wifi_connected')->default(false);
            $table->boolean('camera_ok')->default(false);
            $table->timestamps();
        });

        Schema::create('gps_locations', function (Blueprint $table) {
            $table->id();
            $table->decimal('latitude',  10, 7);
            $table->decimal('longitude', 10, 7);
            $table->integer('akurasi')->default(5);
            $table->string('alamat')->nullable();
            $table->timestamps();
        });

        Schema::create('device_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('wifi')->default('Disconnected');
            $table->string('camera')->default('Error');
            $table->string('bluetooth')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sensor_data');
        Schema::dropIfExists('gps_locations');
        Schema::dropIfExists('device_statuses');
    }
};
