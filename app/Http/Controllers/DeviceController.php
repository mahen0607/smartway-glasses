<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Pastikan ini diimport

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil data manipulasi dari tabel configs
        $configs = DB::table('configs')->pluck('value', 'key');

        // 2. Ambil data asli dari sensor (untuk baterai, dll)
        $device = DB::table('device_statuses')->latest()->first();
        $gps = DB::table('gps_locations')->latest()->first();

        // 3. Kirim ke view (Tambahkan lat, lng, dan zoom)
        return view('dashboard', [
            // Variabel untuk Map (Manipulasi)
            'lat'   => $configs['map_lat'] ?? '-7.9525', 
            'lng'   => $configs['map_lng'] ?? '112.6144',
            'zoom'  => $configs['map_zoom'] ?? '15',
            
            // Variabel pendukung lainnya
            'device' => $device,
            'gps'    => $gps
        ]);
    }
}