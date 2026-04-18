<?php
// database/seeders/DashboardSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DashboardSeeder extends Seeder
{
    public function run(): void
    {
        // GPS – Universitas Brawijaya Malang
        DB::table('gps_locations')->truncate();
        DB::table('gps_locations')->insert([
            'latitude'   => -7.9525,
            'longitude'  => 112.6144,
            'akurasi'    => 3,
            'alamat'     => 'Universitas Brawijaya, Malang, Jawa Timur',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Device status awal
        DB::table('device_statuses')->truncate();
        DB::table('device_statuses')->insert([
            'wifi'          => 'Connected',
            'camera'        => 'Error',
            'battery_pct'   => 50,
            'battery_hours' => 4,
            'gps_active'    => true,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }
}
