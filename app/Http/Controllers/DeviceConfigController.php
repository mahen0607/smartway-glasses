<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeviceConfigController extends Controller
{
    public function index()
    {
        // Ambil data konfigurasi yang ada di database
        $configs = DB::table('configs')->pluck('value', 'key');
        return view('control_center', compact('configs'));
    }

    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'map_lat' => 'required',
            'map_lng' => 'required',
            'map_zoom' => 'required|numeric'
        ]);

        $data = $request->only(['map_lat', 'map_lng', 'map_zoom']);

        // Simpan atau Update ke tabel configs
        foreach ($data as $key => $value) {
            DB::table('configs')->updateOrInsert(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'Lokasi Maps Berhasil Diperbarui!');
    }
}