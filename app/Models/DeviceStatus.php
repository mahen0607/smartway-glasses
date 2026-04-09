<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceStatus extends Model
{
    protected $table = 'device_statuses';

    protected $fillable = [
        'wifi',
        'camera',
        'bluetooth',
    ];
}
