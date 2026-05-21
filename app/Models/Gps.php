<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gps extends Model
{
    // Nama tabel di database
    protected $table = 'gps';

    // Kolom yang boleh diisi
    protected $fillable = ['latitude', 'longitude', 'akurasi'];
}