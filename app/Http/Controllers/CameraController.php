<?php
// app/Http/Controllers/CameraController.php

namespace App\Http\Controllers;

use App\Models\DeviceStatus;
use Illuminate\Http\Request;

class CameraController extends Controller
{
    public function index()
    {
        $device = DeviceStatus::latest()->first();

        // Rekaman dummy — ganti dengan model Recording jika sudah ada
        $recordings = collect(); // kosong = tampil data dummy di blade

        return view('camera', compact('device', 'recordings'));
    }
}
