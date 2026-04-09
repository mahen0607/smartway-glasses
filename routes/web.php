<?php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\GpsController;
use App\Http\Controllers\CameraController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/',         [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/sensor',   [SensorController::class,   'index'])->name('sensor');
    Route::get('/gps',      [GpsController::class,      'index'])->name('gps');
    Route::get('/camera',   [CameraController::class,   'index'])->name('camera');
    Route::get('/settings', fn() => view('settings'))            ->name('settings');

    // API realtime polling
    Route::get('/api/sensor/realtime', [DashboardController::class, 'realtimeData'])
         ->name('api.sensor.realtime');
});

require __DIR__.'/auth.php';
