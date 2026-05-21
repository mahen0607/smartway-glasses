<?php
// ============================================================
// app/Models/GpsData.php
// ============================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GpsData extends Model
{
    protected $table = 'gps_data';

    protected $fillable = [
        'latitude',
        'longitude',
        'akurasi',
        'status',
        'alamat',
    ];

    protected $casts = [
        'latitude'  => 'float',
        'longitude' => 'float',
        'akurasi'   => 'float',
    ];
}
