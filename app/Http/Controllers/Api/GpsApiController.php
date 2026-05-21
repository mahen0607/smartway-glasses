<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GpsApiController extends Controller
{
    public function store(Request $request)
    {
        try {

            // simpan data GPS dari ESP32 ke database
            DB::table('gps_locations')->insert([

                'latitude'   => $request->latitude,
                'longitude'  => $request->longitude,
                'akurasi'    => $request->akurasi,
                'status'     => $request->status,
                'alamat'     => null,

                'created_at' => now(),
                'updated_at' => now(),

            ]);

            return response()->json([
                'success' => true,
                'message' => 'GPS berhasil disimpan'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);

        }
    }
}