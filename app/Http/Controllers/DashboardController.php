<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\SensorData;
use App\Models\GpsLocation;
use App\Models\DeviceStatus;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data sensor terbaru
        $sensorData = SensorData::latest()->first();

        // Data GPS terbaru
        $gpsData = GpsLocation::latest()->first();

        // Status device
        $deviceStatus = DeviceStatus::latest()->first();

        // Data chart 24 jam terakhir (per jam)
        $chartRaw = SensorData::selectRaw('
                HOUR(created_at) as jam,
                COUNT(*) as deteksi_count,
                AVG(rotasi) as avg_rotasi
            ')
            ->where('created_at', '>=', Carbon::now()->subHours(24))
            ->groupBy('jam')
            ->orderBy('jam')
            ->get();

        $chartData = [
            'labels'   => $chartRaw->pluck('jam')->map(fn($h) => $h . ':00')->toArray(),
            'halangan' => $chartRaw->pluck('deteksi_count')->toArray(),
            'rotasi'   => $chartRaw->pluck('avg_rotasi')->map(fn($v) => round($v))->toArray(),
        ];

        return view('dashboard', compact('sensorData', 'gpsData', 'deviceStatus', 'chartData'));
    }

    /**
     * API endpoint untuk realtime polling dari frontend
     */
    public function realtimeData()
    {
        $latest = SensorData::latest()->first();
        $gps    = GpsLocation::latest()->first();

        if (!$latest) {
            return response()->json([]);
        }

        // Hitung durasi pemakaian (dari waktu mulai hari ini)
        $mulai = SensorData::whereDate('created_at', today())->oldest()->first();
        $durasi = $mulai
            ? $this->formatDurasi(now()->diffInMinutes($mulai->created_at))
            : '0h00m';

        return response()->json([
            'terdekat'     => number_format($latest->jarak_terdekat, 1),
            'rotasi'       => $latest->rotasi,
            'total_deteksi'=> SensorData::whereDate('created_at', today())->count(),
            'jarak_min'    => number_format($latest->jarak_min, 1),
            'jarak_maks'   => number_format($latest->jarak_maks, 1),
            'suhu'         => $latest->suhu,
            'durasi'       => $durasi,
            'latitude'     => $gps?->latitude,
            'longitude'    => $gps?->longitude,
            'akurasi'      => $gps?->akurasi,
            'wifi_status'  => $latest->wifi_connected ? 'Connected' : 'Disconnected',
            'camera_status'=> $latest->camera_ok ? 'Active' : 'Error',
        ]);
    }

    private function formatDurasi(int $minutes): string
    {
        $h = intdiv($minutes, 60);
        $m = $minutes % 60;
        return $h . 'h' . str_pad($m, 2, '0', STR_PAD_LEFT) . 'm';
    }
}
