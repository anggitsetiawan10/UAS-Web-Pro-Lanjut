<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisRumah extends Model
{
    use HasFactory;

    protected $table = 'tm_jenis_rumah';
    protected $primaryKey = 'id_jenis';
    // public $timestamps = true;

    protected $fillable = ['jenis_rumah'];
}
