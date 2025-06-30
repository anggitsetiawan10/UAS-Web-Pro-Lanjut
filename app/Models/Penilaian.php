<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;

    protected $table = 'tb_penilaian';
    protected $primaryKey = 'kode_penerima';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_penerima',
        'skor_rumah',
        'skor_kendaraan',
        'skor_pendapatan',
        'skor_tanggungan',
        'skor_total',
        'kode_status',
        'catatan',
        'tanggal_penilaian',
    ];

    // 🔗 Relasi
    public function penerima()
    {
        return $this->belongsTo(PenerimaBantuan::class, 'kode_penerima', 'kode_penerima');
    }

    public function status()
    {
        return $this->belongsTo(StatusKelayakan::class, 'kode_status', 'id_status');
    }

    // 🔥 Fungsi utama SAW
    public static function hitungSAW()
    {
        $data = self::all();

        if ($data->isEmpty()) return;

        // ✅ Cari nilai maksimum untuk normalisasi
        $max_rumah = $data->max('skor_rumah') ?: 1;
        $max_kendaraan = $data->max('skor_kendaraan') ?: 1;
        $max_pendapatan = $data->max('skor_pendapatan') ?: 1;
        $max_tanggungan = $data->max('skor_tanggungan') ?: 1;

        // ✅ Bobot kriteria
        $bobot_rumah = 0.30;
        $bobot_kendaraan = 0.20;
        $bobot_pendapatan = 0.30;
        $bobot_tanggungan = 0.20;

        foreach ($data as $item) {
            $nr_rumah = $item->skor_rumah / $max_rumah;
            $nr_kendaraan = $item->skor_kendaraan / $max_kendaraan;
            $nr_pendapatan = $item->skor_pendapatan / $max_pendapatan;
            $nr_tanggungan = $item->skor_tanggungan / $max_tanggungan;

            $total = 
                ($nr_rumah * $bobot_rumah) +
                ($nr_kendaraan * $bobot_kendaraan) +
                ($nr_pendapatan * $bobot_pendapatan) +
                ($nr_tanggungan * $bobot_tanggungan);

            $status = $total >= 0.7 ? 2 : 1;

            $item->update([
                'skor_total' => round($total, 4),
                'kode_status' => $status
            ]);
        }
    }
    public static function hitungSAWPerPenerima($kode_penerima)
    {
        $data = self::find($kode_penerima);
        if (!$data) {
            return;
        }

        // Cari nilai maksimum dari seluruh data untuk normalisasi
        $max_rumah = self::max('skor_rumah') ?: 1;
        $max_kendaraan = self::max('skor_kendaraan') ?: 1;
        $max_pendapatan = self::max('skor_pendapatan') ?: 1;
        $max_tanggungan = self::max('skor_tanggungan') ?: 1;

        // Bobot
        $bobot_rumah = 0.30;
        $bobot_kendaraan = 0.20;
        $bobot_pendapatan = 0.30;
        $bobot_tanggungan = 0.20;

        // Normalisasi
        $nr_rumah = $data->skor_rumah / $max_rumah;
        $nr_kendaraan = $data->skor_kendaraan / $max_kendaraan;
        $nr_pendapatan = $data->skor_pendapatan / $max_pendapatan;
        $nr_tanggungan = $data->skor_tanggungan / $max_tanggungan;

        $total = 
            ($nr_rumah * $bobot_rumah) +
            ($nr_kendaraan * $bobot_kendaraan) +
            ($nr_pendapatan * $bobot_pendapatan) +
            ($nr_tanggungan * $bobot_tanggungan);

        $status = $total >= 0.7 ? 2 : 1;

        $data->update([
            'skor_total' => round($total, 4),
            'kode_status' => $status
        ]);
    }

}

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

// class Penilaian extends Model
// {
//     use HasFactory;

//     protected $table = 'tb_penilaian';
//     protected $primaryKey = 'kode_penerima';
//     public $incrementing = false;
//     protected $keyType = 'string';

//     protected $fillable = [
//         'kode_penerima',
//         'skor_rumah',
//         'skor_kendaraan',
//         'skor_pendapatan',
//         'skor_tanggungan',
//         'skor_total',
//         'kode_status',
//         'catatan',
//         'tanggal_penilaian',
//     ];
//     protected static function booted()
//     {
//         static::saved(function () {
//             Penilaian::hitungSAW();
//         });
//     }


//     // 🔗 Relasi ke tabel penerima_bantuan
//     public function penerima()
//     {
//         return $this->belongsTo(PenerimaBantuan::class, 'kode_penerima', 'kode_penerima');
//     }

//     // 🔗 Relasi ke tabel status kelayakan
//     public function status()
//     {
//         return $this->belongsTo(StatusKelayakan::class, 'kode_status', 'id_status');
//     }

//     // 🔥 Fungsi untuk menghitung total skor dan menentukan status kelayakan
//     public function hitungTotalDanStatus()
//     {
//         // Gunakan 0 jika null
//         $skor_rumah = $this->skor_rumah ?? 0;
//         $skor_kendaraan = $this->skor_kendaraan ?? 0;
//         $skor_pendapatan = $this->skor_pendapatan ?? 0;
//         $skor_tanggungan = $this->skor_tanggungan ?? 0;

//         // Bobot masing-masing komponen
//         $bobot_rumah = 0.30;
//         $bobot_kendaraan = 0.20;
//         $bobot_pendapatan = 0.30;
//         $bobot_tanggungan = 0.20;

//         // Hitung skor total
//         $total = 
//             ($skor_rumah * $bobot_rumah) +
//             ($skor_kendaraan * $bobot_kendaraan) +
//             ($skor_pendapatan * $bobot_pendapatan) +
//             ($skor_tanggungan * $bobot_tanggungan);

//         // Tentukan status: 2 = Layak, 1 = Tidak Layak
//         $status = $total >= 4 ? 2 : 1;

//         // Simpan hasil
//         $this->skor_total = round($total, 2);
//         $this->kode_status = $status;
//         $this->save();
//     }

//     // 🔥 Fungsi hitung manual tanpa simpan (jika dibutuhkan)
//     public static function hitungTotalDanStatusManual($skor_rumah, $skor_kendaraan, $skor_pendapatan, $skor_tanggungan)
//     {
//         $bobot_rumah = 0.30;
//         $bobot_kendaraan = 0.20;
//         $bobot_pendapatan = 0.30;
//         $bobot_tanggungan = 0.20;

//         $total = 
//             ($skor_rumah * $bobot_rumah) +
//             ($skor_kendaraan * $bobot_kendaraan) +
//             ($skor_pendapatan * $bobot_pendapatan) +
//             ($skor_tanggungan * $bobot_tanggungan);

//         $status = $total >= 4 ? 2 : 1; // 2 = Layak, 1 = Tidak Layak

//         return [
//             'total' => round($total, 2),
//             'status' => $status
//         ];
//     }
//     // 🔥 Fungsi perhitungan total SAW dengan normalisasi
//     public static function hitungSAW()
//     {
//         $data = self::all();

//         if ($data->isEmpty()) {
//             return;
//         }

//         // ✅ Cari nilai maksimum untuk normalisasi
//         $max_rumah = $data->max('skor_rumah') ?: 1;
//         $max_kendaraan = $data->max('skor_kendaraan') ?: 1;
//         $max_pendapatan = $data->max('skor_pendapatan') ?: 1;
//         $max_tanggungan = $data->max('skor_tanggungan') ?: 1;

//         // 🔧 Bobot masing-masing kriteria
//         $bobot_rumah = 0.30;
//         $bobot_kendaraan = 0.20;
//         $bobot_pendapatan = 0.30;
//         $bobot_tanggungan = 0.20;

//         foreach ($data as $item) {
//             // 🔢 Normalisasi
//             $nr_rumah = $item->skor_rumah / $max_rumah;
//             $nr_kendaraan = $item->skor_kendaraan / $max_kendaraan;
//             $nr_pendapatan = $item->skor_pendapatan / $max_pendapatan;
//             $nr_tanggungan = $item->skor_tanggungan / $max_tanggungan;

//             // 🔥 Hitung skor total SAW
//             $total = 
//                 ($nr_rumah * $bobot_rumah) +
//                 ($nr_kendaraan * $bobot_kendaraan) +
//                 ($nr_pendapatan * $bobot_pendapatan) +
//                 ($nr_tanggungan * $bobot_tanggungan);

//             // 🔗 Tentukan status (misal >=0.7 layak)
//             $status = $total >= 0.7 ? 2 : 1;

//             // 💾 Simpan ke database
//             $item->update([
//                 'skor_total' => round($total, 4),
//                 'kode_status' => $status
//             ]);
//         }
//     }

// }

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

// class Penilaian extends Model
// {
//     use HasFactory;

//     protected $table = 'tb_penilaian';
//     protected $primaryKey = 'kode_penerima';
//     public $incrementing = false;
//     protected $keyType = 'string';

//     protected $fillable = [
//         'kode_penerima',
//         'skor_rumah',
//         'skor_kendaraan',
//         'skor_pendapatan',
//         'skor_tanggungan',
//         'skor_total',
//         'kode_status',
//         'catatan',
//         'tanggal_penilaian',
//     ];

//     public function penerima()
//     {
//         return $this->belongsTo(PenerimaBantuan::class, 'kode_penerima', 'kode_penerima');
//     }

//     public function status()
//     {
//         return $this->belongsTo(StatusKelayakan::class, 'kode_status', 'id_status');
//     }

//     // 🔥 Fungsi Hitung Skor Total Manual
//     public static function hitungTotalDanStatusManual($skor_rumah, $skor_kendaraan, $skor_pendapatan, $skor_tanggungan)
//     {
//         $bobot_rumah = 0.30;
//         $bobot_kendaraan = 0.20;
//         $bobot_pendapatan = 0.30;
//         $bobot_tanggungan = 0.20;

//         $total =
//             ($skor_rumah * $bobot_rumah) +
//             ($skor_kendaraan * $bobot_kendaraan) +
//             ($skor_pendapatan * $bobot_pendapatan) +
//             ($skor_tanggungan * $bobot_tanggungan);

//         $status = $total >= 4 ? 2 : 1; // 2 = Layak, 1 = Tidak Layak

//         return [
//             'total' => $total,
//             'status' => $status
//         ];
//     }
// }



// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

// class Penilaian extends Model
// {
//     use HasFactory;

//     protected $table = 'tb_penilaian';
//     protected $primaryKey = 'kode_penerima';
//     public $incrementing = false;
//     protected $keyType = 'string';

//     protected $fillable = [
//         'kode_penerima',
//         'skor_rumah',
//         'skor_kendaraan',
//         'skor_pendapatan',
//         'skor_tanggungan',
//         'skor_total',
//         'kode_status',
//         'catatan',
//         'tanggal_penilaian',
//     ];

//     public function penerima()
//     {
//         return $this->belongsTo(PenerimaBantuan::class, 'kode_penerima', 'kode_penerima');
//     }

//     public function status()
//     {
//         return $this->belongsTo(StatusKelayakan::class, 'kode_status', 'id_status');
//     }
// }
