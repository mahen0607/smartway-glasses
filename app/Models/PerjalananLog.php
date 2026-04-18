<?php
// app/Models/PerjalananLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerjalananLog extends Model
{
    protected $table    = 'perjalanan_logs';
    protected $fillable = ['deskripsi','jarak','status','arah'];
}
