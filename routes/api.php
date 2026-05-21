<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\Api\SensorApiController;
use App\Http\Controllers\Api\GpsApiController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceConfigController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Endpoint untuk Python Simulator
Route::post('/ingest', [SensorController::class, 'ingest']);

// Endpoint untuk Dashboard
Route::get('/dashboard/data', [SensorController::class, 'getData']);

Route::middleware(['web', 'auth'])->group(function () {
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');});

// Halaman manipulasi data (Control Center)
Route::get('/control-center', [DeviceConfigController::class, 'index'])->name('control.center');
Route::post('/control-center/update', [DeviceConfigController::class, 'update'])->name('control.update');

// Endpoint device status & realtime (dipanggil JS dari web)
Route::get('/device/status',   [SensorController::class, 'deviceStatus']);
Route::get('/sensor/realtime', [SensorController::class, 'realtime']);
Route::get('/gps/realtime',    [SensorController::class, 'gpsRealtime']);

// Command System
Route::post('/command/send', function (Request $request) {
    \Illuminate\Support\Facades\DB::table('commands')->insert([
        'device_id'  => $request->device_id,
        'command'    => $request->command,
        'status'     => 'pending',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    return response()->json(['message' => 'Command sent'], 201);
});

// ── Endpoint untuk ESP32 ──────────────────────────────────
// Hapus ->name() karena konflik dengan route di web.php
Route::post('/esp32/sensor', [SensorApiController::class, 'store']);
Route::post('/esp32/gps',    [GpsApiController::class,    'store']);



Route::post('/sensor/toggle', [DeviceController::class, 'toggleSensor'])->name('api.sensor.toggle');
Route::get('/device/status', [DeviceController::class, 'getStatus'])->name('api.device.status');
