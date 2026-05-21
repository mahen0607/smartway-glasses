<?php
// app/Http/Controllers/Api/SensorApiController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SensorApiController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        // Debug — lihat semua data yang masuk
        Log::info('[ESP32 Sensor masuk]', $request->all());

        // Cek token
        $token = env('ESP32_API_TOKEN', 'token-rahasia-esp32');
        if ($request->api_token !== $token) {
            Log::warning('[ESP32] Token salah: ' . $request->api_token);
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $jarakTerdekat = $request->jarak_terdekat ?? 999;
        $status        = $request->status         ?? 'Aman';
        $arah          = $request->arah            ?? 'Depan';

        // Simpan ke sensor_data — pakai DB::table agar tidak error model
        $id = DB::table('sensor_data')->insertGetId([
            'jarak_terdekat' => $jarakTerdekat,
            'jarak_min'      => $request->jarak_min    ?? $jarakTerdekat,
            'jarak_maks'     => $request->jarak_maks   ?? 0,
            'arah'           => $arah,
            'status'         => $status,
            'rotasi'         => 0,
            'suhu'           => 38,
            'total_deteksi'  => $request->total_deteksi ?? 0,
            'wifi_connected' => 1,
            'camera_ok'      => 0,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        // Update device_statuses
        DB::table('device_statuses')->updateOrInsert(
            ['id' => 1],
            ['wifi' => 'Connected', 'camera' => 'Error',
             'updated_at' => now(), 'created_at' => now()]
        );

        // Log perjalanan jika ada halangan
        if (in_array($status, ['Waspada', 'Bahaya'])) {
            DB::table('perjalanan_logs')->insert([
                'deskripsi'  => 'Objek ' . strtolower($status) .
                                ' (' . strtolower($arah) . ')',
                'jarak'      => $jarakTerdekat,
                'status'     => $status,
                'arah'       => $arah,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json(['success' => true, 'id' => $id], 201);
    }
}
