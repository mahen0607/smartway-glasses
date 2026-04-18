<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/* Pastikan import Controller ini ada di paling atas */
use App\Http\Controllers\SensorController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Endpoint untuk Python Simulator (Monitoring)
// URL: http://127.0.0.1:8000/api/ingest
Route::post('/ingest', [SensorController::class, 'ingest']);

// Endpoint untuk Dashboard (Real-time data)
// URL: http://127.0.0.1:8000/api/dashboard/data
Route::get('/dashboard/data', [SensorController::class, 'getData']);

// Endpoint tambahan untuk Command System (Control)
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