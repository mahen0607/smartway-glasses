<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\DeviceStatus;
use App\Models\GpsLocation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index()
    {
        $device = DeviceStatus::latest()->first();
        $gps    = GpsLocation::latest()->first();

        return view('dashboard', compact('device', 'gps'));
    }

    /** GET /api/device/status  – polling dari frontend */
    public function deviceStatus(): JsonResponse
    {
        $d = DeviceStatus::latest()->first();

        return response()->json([
            'wifi'        => $d?->wifi        ?? 'Connected',
            'camera'      => $d?->camera      ?? 'Error',
            'battery_pct' => $d?->battery_pct ?? 50,
        ]);
    }

    /** POST /api/sensor/toggle – ubah status sensor */
    public function sensorToggle(Request $request): JsonResponse
    {
        $request->validate([
            'sensor' => 'required|string',
            'value'  => 'required|boolean',
        ]);

        // Simpan ke DB jika ada model SensorStatus
        // SensorStatus::updateOrCreate(['name'=>$request->sensor],['active'=>$request->value]);

        return response()->json(['ok' => true]);
    }
}
