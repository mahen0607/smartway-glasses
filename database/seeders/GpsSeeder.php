<?php
// database/seeders/GpsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GpsSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('gps_locations')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Titik-titik lokasi sekitar Universitas Brawijaya Malang
        $titik = [
            ['lat' => -7.9525, 'lng' => 112.6144, 'alamat' => 'Universitas Brawijaya, Malang'],
            ['lat' => -7.9521, 'lng' => 112.6140, 'alamat' => 'Jl. Veteran, Malang'],
            ['lat' => -7.9518, 'lng' => 112.6138, 'alamat' => 'Gerbang UB Malang'],
            ['lat' => -7.9515, 'lng' => 112.6135, 'alamat' => 'Jl. Kertosari'],
            ['lat' => -7.9510, 'lng' => 112.6130, 'alamat' => 'Jl. MT Haryono'],
            ['lat' => -7.9505, 'lng' => 112.6125, 'alamat' => 'Jl. Soekarno Hatta'],
            ['lat' => -7.9500, 'lng' => 112.6120, 'alamat' => 'Jembatan Soekarno Hatta'],
            ['lat' => -7.9508, 'lng' => 112.6132, 'alamat' => 'Jl. Kertosari No.5'],
            ['lat' => -7.9512, 'lng' => 112.6136, 'alamat' => 'SIP Universitas Brawijaya'],
            ['lat' => -7.9519, 'lng' => 112.6141, 'alamat' => 'Universitas Brawijaya'],
        ];

        foreach ($titik as $i => $t) {
            DB::table('gps_locations')->insert([
                'latitude'   => $t['lat'],
                'longitude'  => $t['lng'],
                'akurasi'    => rand(2, 6),
                'status'     => $i % 3 === 0 ? 'Diam' : 'Bergerak',
                'alamat'     => $t['alamat'],
                'created_at' => Carbon::now()->subMinutes($i * 3),
                'updated_at' => Carbon::now()->subMinutes($i * 3),
            ]);
        }

        $this->command->info('GpsSeeder selesai! ' . count($titik) . ' lokasi tersimpan.');
    }
}
