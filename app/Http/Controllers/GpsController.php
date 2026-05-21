<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GpsController extends Controller
{
    /**
     * Halaman GPS
     */
    public function index()
    {
        // Ambil GPS terbaru dari database
        $latest = DB::table('gps_locations')
            ->orderBy('id', 'desc')
            ->first();

        // Jika belum ada data
        if (!$latest) {
            $latest = (object)[
                'latitude' => -7.9525,
                'longitude' => 112.6144,
                'akurasi' => 5,
                'status' => 'Offline',
                'created_at' => now()
            ];
        }

        // Riwayat GPS
        $histories = DB::table('gps_locations')
            ->orderBy('id', 'desc')
            ->take(20)
            ->get();

        return view('gps', [
            'gps' => $latest,
            'histories' => $histories
        ]);
    }

    /**
     * API realtime untuk Leaflet
     */
    public function getRealtime()
    {
        $latest = DB::table('gps_locations')
            ->orderBy('id', 'desc')
            ->first();

        if (!$latest) {
            return response()->json([
                'success' => false
            ]);
        }

        return response()->json([
            'success'   => true,
            'latitude'  => (float)$latest->latitude,
            'longitude' => (float)$latest->longitude,
            'akurasi'   => $latest->akurasi ?? 0,
            'status'    => $latest->status ?? 'Aktif',
            'updated_at'=> $latest->created_at
        ]);
    }
}