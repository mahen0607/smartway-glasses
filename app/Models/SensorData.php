<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
    protected $fillable = [
        'jarak_terdekat',
        'jarak_min',
        'jarak_maks',
        'rotasi',
        'suhu',
        'total_deteksi',
        'wifi_connected',
        'camera_ok',
    ];

    protected $casts = [
        'wifi_connected' => 'boolean',
        'camera_ok'      => 'boolean',
        'jarak_terdekat' => 'float',
        'jarak_min'      => 'float',
        'jarak_maks'     => 'float',
    ];
}
