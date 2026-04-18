<?php
// app/Http/Controllers/SensorController.php

namespace App\Http\Controllers;

use App\Models\SensorData;
use App\Models\GpsLocation;
use App\Models\DeviceStatus;
use App\Models\PerjalananLog;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class SensorController extends Controller
{
    public function index()
    {
        $sensor = SensorData::latest()->first();
        $gps    = GpsLocation::latest()->first();
        $device = DeviceStatus::latest()->first();

        $logs = PerjalananLog::whereDate('created_at', today())
                    ->latest()->limit(10)->get();

        $mulai  = SensorData::whereDate('created_at', today())->oldest()->first();
        $durasi = $mulai
            ? $this->fmtDurasi(now()->diffInMinutes($mulai->created_at))
            : '0h00m';

        $chartRaw = SensorData::selectRaw('
                HOUR(created_at) as jam,
                COUNT(*) as cnt,
                AVG(rotasi) as avg_rot
            ')
            ->where('created_at', '>=', now()->subHours(24))
            ->groupBy('jam')->orderBy('jam')->get();

        $chartData = [
            'labels'   => $chartRaw->pluck('jam')->map(fn($h) => $h.':00')->toArray(),
            'halangan' => $chartRaw->pluck('cnt')->map(fn($v) => (int)$v)->toArray(),
            'rotasi'   => $chartRaw->pluck('avg_rot')->map(fn($v) => round($v))->toArray(),
        ];

        return view('sensor', compact('sensor','gps','device','logs','durasi','chartData'));
    }

    public function realtime(): JsonResponse
    {
        $s = SensorData::latest()->first();
        $g = GpsLocation::latest()->first();
        $d = DeviceStatus::latest()->first();

        if (!$s) return response()->json([]);

        $jarak  = $s->jarak_terdekat ?? 0;
        $status = $jarak < 0.3 ? 'Bahaya' : ($jarak < 0.7 ? 'Waspada' : 'Aman');

        $mulai  = SensorData::whereDate('created_at', today())->oldest()->first();
        $durasi = $mulai
            ? $this->fmtDurasi(now()->diffInMinutes($mulai->created_at))
            : '0h00m';

        return response()->json([
            'jarak_terdekat' => round($jarak, 2),
            'rotasi'         => $s->rotasi,
            'total_deteksi'  => SensorData::whereDate('created_at', today())->count(),
            'durasi'         => $durasi,
            'jarak_status'   => $status,
            'arah'           => $s->arah ?? 'Depan',
            'battery_pct'    => $d?->battery_pct  ?? 50,
            'wifi'           => $d?->wifi          ?? 'Connected',
            'lat'            => $g?->latitude,
            'lng'            => $g?->longitude,
            'gps_status'     => 'bergerak',
            'log_baru'       => 'Objek '.strtolower($status).' ('.($s->arah ?? 'depan').')',
        ]);
    }

    private function fmtDurasi(int $m): string
    {
        return intdiv($m,60).'h'.str_pad($m%60,2,'0',STR_PAD_LEFT).'m';
    }
}
