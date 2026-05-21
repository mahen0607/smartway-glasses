<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil data manipulasi dari tabel configs
        // Jika tabel belum ada atau kosong, pluck akan menghasilkan array kosong
        try {
            $configs = DB::table('configs')->pluck('value', 'key');
        } catch (\Exception $e) {
            $configs = collect(); // Antisipasi jika tabel belum dibuat
        }

        // 2. Ambil data status kacamata (baterai, dll)
        $device = DB::table('device_statuses')->latest()->first();
        
        // 3. Ambil data GPS asli (opsional, jika ingin ditampilkan)
        $gps_asli = DB::table('gps_locations')->latest()->first();

        // 4. Kirim semua variabel ke view dashboard
        return view('dashboard', [
            // Variabel Map yang menyebabkan error
            'lat'    => $configs['map_lat'] ?? '-7.9525', 
            'lng'    => $configs['map_lng'] ?? '112.6144',
            'zoom'   => $configs['map_zoom'] ?? '15',
            
            // Variabel pendukung lainnya
            'device' => $device,
            'gps'    => $gps_asli
        ]);
    }
}