<?php
// database/seeders/SensorSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SensorSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sensor_data')->truncate();
        DB::table('perjalanan_logs')->truncate();

        $arahs = ['Depan','Kiri','Kanan','Belakang'];

        // 25 record sensor (tiap jam)
        for ($i = 24; $i >= 0; $i--) {
            $jarak  = round(0.2 + rand(0,50)/100, 2);
            $status = $jarak < 0.3 ? 'Bahaya' : ($jarak < 0.7 ? 'Waspada' : 'Aman');
            DB::table('sensor_data')->insert([
                'jarak_terdekat' => $jarak,
                'jarak_min'      => round(0.2 + rand(0,10)/100, 2),
                'jarak_maks'     => round(3.5 + rand(0,20)/10, 1),
                'arah'           => $arahs[array_rand($arahs)],
                'status'         => $status,
                'rotasi'         => rand(450,680),
                'suhu'           => rand(36,42),
                'total_deteksi'  => rand(400,500),
                'wifi_connected' => 1,
                'camera_ok'      => 0,
                'created_at'     => Carbon::now()->subHours($i),
                'updated_at'     => Carbon::now()->subHours($i),
            ]);
        }

        // 10 log perjalanan hari ini
        $desks = [
            'Objek dekat (depan)',
            'Objek dekat (kiri)',
            'Jalan bebas hambatan',
            'Peringatan rintangan',
            'Belokan terdeteksi',
        ];
        for ($i = 0; $i < 10; $i++) {
            DB::table('perjalanan_logs')->insert([
                'deskripsi'  => $desks[array_rand($desks)],
                'jarak'      => round(0.3 + rand(0,40)/100, 2),
                'status'     => ['Aman','Waspada','Bahaya'][rand(0,2)],
                'arah'       => $arahs[array_rand($arahs)],
                'created_at' => Carbon::now()->subMinutes($i * 2),
                'updated_at' => Carbon::now()->subMinutes($i * 2),
            ]);
        }
    }
}
