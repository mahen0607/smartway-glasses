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
        Schema::create('gps', function ($table) {
            $table->id();
            $table->string('latitude')->nullable(); // Pastikan baris ini ada
            $table->string('longitude')->nullable(); // Pastikan baris ini ada
            $table->integer('akurasi')->default(3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gps');
    }
};
