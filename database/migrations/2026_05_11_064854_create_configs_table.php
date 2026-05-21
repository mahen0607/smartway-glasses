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
        // Kita gunakan nama tabel 'configs' agar bisa menyimpan data lain selain GPS
        Schema::create('configs', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Contoh: 'map_lat', 'map_lng'
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configs');
    }
};