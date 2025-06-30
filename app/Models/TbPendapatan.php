<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbPendapatan extends Model
{
    protected $table = 'tb_pendapatan';
    protected $primaryKey = 'kode_pendapatan';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kode_pendapatan',
        'kode_penerima',
        'kode_profesi',
        'pendapatan_bulanan',
    ];

    // 🔗 Relasi ke penerima
    public function penerima()
    {
        return $this->belongsTo(PenerimaBantuan::class, 'kode_penerima', 'kode_penerima');
    }

    // 🔗 Relasi ke profesi
    public function profesi()
    {
        return $this->belongsTo(JenisPekerjaan::class, 'kode_profesi', 'id_profesi');
    }
}
