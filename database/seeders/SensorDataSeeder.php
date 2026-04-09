<?php
namespace Database\Seeders;
use App\Models\SensorData;
use App\Models\GpsLocation;
use App\Models\DeviceStatus;
use Illuminate\Database\Seeder;

class SensorDataSeeder extends Seeder
{
    public function run(): void
    {
        // GPS seed - Universitas Brawijaya
        GpsLocation::create([
            'latitude'  => -7.9525,
            'longitude' => 112.6144,
            'akurasi'   => 3,
            'alamat'    => 'Universitas Brawijaya, Malang, Jawa Timur',
        ]);

        // Device status seed
        DeviceStatus::create([
            'wifi'   => 'Connected',
            'camera' => 'Error',
        ]);

        // Sensor data sample - 24 jam
        for ($i = 24; $i >= 0; $i--) {
            SensorData::create([
                'jarak_terdekat' => round(0.3 + rand(0, 35) / 10, 1),
                'jarak_min'      => round(0.3 + rand(0, 5) / 10, 1),
                'jarak_maks'     => round(3.5 + rand(0, 15) / 10, 1),
                'rotasi'         => rand(450, 680),
                'suhu'           => rand(36, 42),
                'total_deteksi'  => rand(400, 500),
                'wifi_connected' => true,
                'camera_ok'      => false,
                'created_at'     => now()->subHours($i),
                'updated_at'     => now()->subHours($i),
            ]);
        }
    }
}
