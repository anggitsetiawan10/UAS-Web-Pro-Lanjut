<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KondisiRumah extends Model
{
    use HasFactory;

    protected $table = 'tm_kondisi_rumah';
    protected $primaryKey = 'id_kondisi';

    protected $fillable = ['kondisi_rumah'];
}
