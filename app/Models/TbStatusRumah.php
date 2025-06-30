<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbStatusRumah extends Model
{
    use HasFactory;

    protected $table = 'tb_status_rumah';
    protected $primaryKey = 'kode_kepemilikan_rumah';
    public $incrementing = false;

    protected $fillable = [
        'kode_kepemilikan_rumah',
        'kode_penerima',
        'id_status_rumah',
        'luas_rumah',
        'id_kondisi',
        'id_jenis',
        'jumlah_penghuni',
    ];

    public function penerima()
    {
        return $this->belongsTo(PenerimaBantuan::class, 'kode_penerima', 'kode_penerima');
    }

    public function status()
    {
        return $this->belongsTo(StatusRumah::class, 'id_status_rumah', 'id_status');
    }

    public function kondisi()
    {
        return $this->belongsTo(KondisiRumah::class, 'id_kondisi', 'id_kondisi');
    }

    public function jenis()
    {
        return $this->belongsTo(JenisRumah::class, 'id_jenis', 'id_jenis');
    }
}
