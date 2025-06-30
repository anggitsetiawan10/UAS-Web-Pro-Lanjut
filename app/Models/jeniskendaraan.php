<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisKendaraan extends Model
{
    use HasFactory;

    protected $table = 'tm_jenis_kendaraan';
    protected $primaryKey = 'id_jenis_kendaraan';
    protected $fillable = ['id_jenis_kendaraan', 'jenis'];
}
