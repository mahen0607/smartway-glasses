<?php
// database/migrations/2024_01_01_000004_update_gps_locations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Buat tabel jika belum ada
        if (!Schema::hasTable('gps_locations')) {
            Schema::create('gps_locations', function (Blueprint $table) {
                $table->id();
                $table->decimal('latitude',  10, 7)->default(-7.9525);
                $table->decimal('longitude', 10, 7)->default(112.6144);
                $table->unsignedSmallInteger('akurasi')->default(3);
                $table->string('status')->default('Bergerak');
                $table->string('alamat')->nullable();
                $table->timestamps();
            });
        } else {
            // Tambah kolom status jika belum ada
            Schema::table('gps_locations', function (Blueprint $table) {
                if (!Schema::hasColumn('gps_locations', 'status')) {
                    $table->string('status')->default('Bergerak')->after('akurasi');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('gps_locations', 'status')) {
            Schema::table('gps_locations', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
