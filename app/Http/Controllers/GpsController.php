<?php
// app/Http/Controllers/GpsController.php

namespace App\Http\Controllers;

use App\Models\GpsLocation;
use Illuminate\Http\JsonResponse;

class GpsController extends Controller
{
    public function index()
    {
        // Koordinat terbaru
        $gps = GpsLocation::latest()->first();

        // Riwayat 10 lokasi terakhir hari ini
        $histories = GpsLocation::whereDate('created_at', today())
                        ->latest()
                        ->limit(10)
                        ->get();

        return view('gps', compact('gps', 'histories'));
    }

    /**
     * GET /api/gps/realtime
     * Dipanggil JS setiap 5 detik
     */
    public function realtime(): JsonResponse
    {
        $g = GpsLocation::latest()->first();

        if (!$g) return response()->json([]);

        return response()->json([
            'latitude'  => $g->latitude,
            'longitude' => $g->longitude,
            'akurasi'   => $g->akurasi,
            'status'    => $g->status ?? 'Bergerak',
            'alamat'    => $g->alamat,
        ]);
    }
}
