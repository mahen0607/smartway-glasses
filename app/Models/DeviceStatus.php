<?php
// app/Models/DeviceStatus.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceStatus extends Model
{
    protected $table = 'device_statuses';

    protected $fillable = [
        'wifi',
        'camera',
        'battery_pct',
        'battery_hours',
        'gps_active',
    ];

    protected $casts = [
        'gps_active'    => 'boolean',
        'battery_pct'   => 'integer',
        'battery_hours' => 'integer',
    ];
}
