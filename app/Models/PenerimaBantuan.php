<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenerimaBantuan extends Model
{
    protected $table = 'penerima_bantuan';

    protected $primaryKey = 'kode_penerima';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'kode_penerima',
        'nik',
        'nama',
        'alamat',
        'tanggal_lahir',
        'jenis_kelamin',
        'kontak',
        'tanggal_survei',
    ];
    public function penilaian()
    {
        return $this->hasOne(Penilaian::class, 'kode_penerima', 'kode_penerima');
    }

}
