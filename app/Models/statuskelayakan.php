<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StatusKelayakan extends Model
{
    use HasFactory;

    protected $table = 'tm_status_kelayakan';
    protected $primaryKey = 'id_status';

    protected $fillable = ['nama_status'];
}
