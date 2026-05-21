<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gps_locations_data', function (Blueprint $table) {
            $table->id();
            $table->string('latitude')->default('-7.9525');
            $table->string('longitude')->default('112.6144');
            $table->integer('akurasi')->default(3);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gps_locations_data');
    }
};