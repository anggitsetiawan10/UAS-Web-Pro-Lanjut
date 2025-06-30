<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    protected $table = 'tb_kendaraan';
    protected $primaryKey = 'kode_kendaraan';
    public $incrementing = false; // Karena primary key bukan auto increment
    protected $keyType = 'string'; // Karena primary key tipe VARCHAR

    protected $fillable = [
        'kode_kendaraan',
        'kode_penerima',
        'id_jenis_kendaraan',
        'jumlah',
    ];

    // Relasi ke penerima bantuan
    public function penerima()
    {
        return $this->belongsTo(PenerimaBantuan::class, 'kode_penerima', 'kode_penerima');
    }

    // Relasi ke jenis kendaraan
    public function jenisKendaraan()
    {
        return $this->belongsTo(JenisKendaraan::class, 'id_jenis_kendaraan', 'id_jenis_kendaraan');
    }
}
