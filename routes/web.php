<?php
// routes/web.php — TIMPA file lama

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\GpsController;
use App\Http\Controllers\CameraController;
use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceConfigController;

/* Breeze auth routes */
require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {

    /* ── Halaman ── */
    Route::get('/dashboard',[DashboardController::class, 'index'])->name('dashboard');
    Route::get('/sensor',   [SensorController::class,   'index'])->name('sensor');
    Route::get('/gps',      [GpsController::class,      'index'])->name('gps');
    Route::get('/camera',   [CameraController::class,   'index'])->name('camera');
    Route::get('/settings', fn() => view('settings')            )->name('settings');

    /* ── Account update ── */
    Route::post('/settings/akun', [AccountController::class, 'update'])->name('settings.akun');

    /* ── API ── */
    Route::get ('/api/device/status',   [DashboardController::class, 'deviceStatus'])->name('api.device.status');
    Route::post('/api/sensor/toggle',   [DashboardController::class, 'sensorToggle'])->name('api.sensor.toggle');
    Route::get ('/api/sensor/realtime', [SensorController::class,        'realtime'])->name('api.sensor.realtime');
    Route::get('/api/gps/realtime',     [GpsController::class,        'getRealtime'])->name('api.gps.realtime');

    // Halaman untuk input koordinat
    Route::get('/control-center', [DeviceConfigController::class, 'index'])->name('control.center');
    // Proses simpan koordinat
    Route::post('/control-center/update', [DeviceConfigController::class, 'update'])->name('control.update');
});

