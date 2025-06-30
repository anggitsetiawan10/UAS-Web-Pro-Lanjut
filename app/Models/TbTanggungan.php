<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbTanggungan extends Model
{
    use HasFactory;

    protected $table = 'tb_tanggungan';
    protected $primaryKey = 'kode_tanggungan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_tanggungan',
        'kode_penerima',
        'jumlah_anak',
        'anak_sekolah',
        'anak_belum_sekolah',
        'jumlah_tanggungan_lain',
    ];

    public function penerima()
    {
        return $this->belongsTo(PenerimaBantuan::class, 'kode_penerima', 'kode_penerima');
    }
}
