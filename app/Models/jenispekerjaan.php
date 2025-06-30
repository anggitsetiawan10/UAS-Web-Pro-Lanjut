<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisPekerjaan extends Model
{
    use HasFactory;

    protected $table = 'tm_jenis_pekerjaan';
    protected $primaryKey = 'id_profesi';

    protected $fillable = ['nama_profesi'];
}
