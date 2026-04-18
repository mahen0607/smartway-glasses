<?php
// app/Models/GpsLocation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GpsLocation extends Model
{
    protected $table = 'gps_locations';

    protected $fillable = [
        'latitude',
        'longitude',
        'akurasi',
        'alamat',
    ];

    protected $casts = [
        'latitude'  => 'float',
        'longitude' => 'float',
        'akurasi'   => 'integer',
    ];
}
