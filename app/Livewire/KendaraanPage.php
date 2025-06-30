<?php

namespace App\Livewire;

use App\Models\Kendaraan;
use App\Models\JenisKendaraan;
use App\Models\PenerimaBantuan;
use App\Models\Penilaian;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

class KendaraanPage extends Component
{
    use WithPagination;

    #[Layout('components.layouts.app')]
    protected $paginationTheme = 'bootstrap';

    public $kode_kendaraan;
    public $kode_penerima;
    public $id_jenis_kendaraan;
    public $jumlah;

    public function mount()
    {
        $this->generateKode();
    }

    public function render()
    {
        return view('livewire.kendaraan', [
            'data_kendaraan' => Kendaraan::with(['penerima', 'jenisKendaraan'])->paginate(10),
            'jenis_kendaraan' => JenisKendaraan::all(),
            'data_penerima' => PenerimaBantuan::all(),
        ]);
    }

    public function simpan()
    {
        $this->validate([
            'kode_kendaraan' => 'required',
            'kode_penerima' => 'required',
            'id_jenis_kendaraan' => 'required',
            'jumlah' => 'required|integer|min:0',
        ]);

        // 🔥 Simpan data kendaraan
        Kendaraan::updateOrCreate(
            ['kode_kendaraan' => $this->kode_kendaraan],
            [
                'kode_penerima' => $this->kode_penerima,
                'id_jenis_kendaraan' => $this->id_jenis_kendaraan,
                'jumlah' => $this->jumlah,
            ]
        );

        // 🔥 Hitung skor kendaraan berdasarkan aturan
        $skor = $this->hitungSkor();

        // 🔥 Update nilai skor_kendaraan di tabel penilaian
        Penilaian::updateOrCreate(
            ['kode_penerima' => $this->kode_penerima],
            [
                'skor_kendaraan' => $skor,
                'tanggal_penilaian' => now()
            ]
        );

        /**
         * ❗ Tidak perlu memanggil hitungSAW() atau hitungTotalDanStatus()
         * karena sudah otomatis berjalan lewat event `saved()` di Model Penilaian
         */
        Penilaian::hitungSAWPerPenerima($this->kode_penerima);

        session()->flash('message', 'Data kendaraan berhasil disimpan.');
        $this->resetInput();
        $this->generateKode();
    }

    private function hitungSkor()
    {
        if ($this->id_jenis_kendaraan == 3) {
            return 5; // ✅ Tidak memiliki kendaraan
        }

        if ($this->id_jenis_kendaraan == 2) {
            // ✅ Motor
            return ($this->jumlah >= 3) ? 4 : 3;
        }

        if ($this->id_jenis_kendaraan == 1) {
            return 1; // ✅ Mobil
        }

        return 5;
    }

    public function edit($id)
    {
        $data = Kendaraan::findOrFail($id);
        $this->kode_kendaraan = $data->kode_kendaraan;
        $this->kode_penerima = $data->kode_penerima;
        $this->id_jenis_kendaraan = $data->id_jenis_kendaraan;
        $this->jumlah = $data->jumlah;
    }

    public function delete($id)
    {
        // Cari data kendaraan yang mau dihapus
        $data = Kendaraan::findOrFail($id);

        // Simpan kode penerima sebelum data dihapus
        $kode_penerima = $data->kode_penerima;

        // Hapus data kendaraan
        $data->delete();

        // 🔥 Update skor_kendaraan jadi 5 (anggap tidak punya kendaraan)
        Penilaian::updateOrCreate(
            ['kode_penerima' => $kode_penerima],
            [
                'skor_kendaraan' => 5, // skor default jika tidak punya kendaraan
                'tanggal_penilaian' => now()
            ]
        );

        // 🔥 Hitung ulang SAW khusus untuk penerima itu
        Penilaian::hitungSAWPerPenerima($kode_penerima);

        // Notifikasi
        session()->flash('message', 'Data kendaraan berhasil dihapus.');
    }

    public function resetInput()
    {
        $this->kode_penerima = '';
        $this->id_jenis_kendaraan = '';
        $this->jumlah = '';
    }

    private function generateKode()
    {
        $last = Kendaraan::orderBy('kode_kendaraan', 'desc')->first();
        if (!$last) {
            $this->kode_kendaraan = 'KEN-001';
        } else {
            $num = (int)substr($last->kode_kendaraan, 4) + 1;
            $this->kode_kendaraan = 'KEN-' . str_pad($num, 3, '0', STR_PAD_LEFT);
        }
    }
}

// namespace App\Livewire;

// use App\Models\Kendaraan;
// use App\Models\JenisKendaraan;
// use App\Models\PenerimaBantuan;
// use App\Models\Penilaian;
// use Livewire\Component;
// use Livewire\Attributes\Layout;
// use Livewire\WithPagination;

// class KendaraanPage extends Component
// {
//     use WithPagination;

//     #[Layout('components.layouts.app')]
//     protected $paginationTheme = 'bootstrap';

//     public $kode_kendaraan;
//     public $kode_penerima;
//     public $id_jenis_kendaraan;
//     public $jumlah;

//     public function mount()
//     {
//         $this->generateKode();
//     }

//     public function render()
//     {
//         return view('livewire.kendaraan', [
//             'data_kendaraan' => Kendaraan::with(['penerima', 'jenisKendaraan'])->paginate(10),
//             'jenis_kendaraan' => JenisKendaraan::all(),
//             'data_penerima' => PenerimaBantuan::all(),
//         ]);
//     }

//     public function simpan()
//     {
//         $this->validate([
//             'kode_kendaraan' => 'required',
//             'kode_penerima' => 'required',
//             'id_jenis_kendaraan' => 'required',
//             'jumlah' => 'required|integer|min:0',
//         ]);

//         Kendaraan::updateOrCreate(
//             ['kode_kendaraan' => $this->kode_kendaraan],
//             [
//                 'kode_penerima' => $this->kode_penerima,
//                 'id_jenis_kendaraan' => $this->id_jenis_kendaraan,
//                 'jumlah' => $this->jumlah,
//             ]
//         );

//         $skor = $this->hitungSkor();

//         $penilaian = Penilaian::updateOrCreate(
//             ['kode_penerima' => $this->kode_penerima],
//             ['skor_kendaraan' => $skor, 'tanggal_penilaian' => now()]
//         );

//         $penilaian->hitungTotalDanStatus();

//         session()->flash('message', 'Data kendaraan berhasil disimpan.');
//         $this->resetInput();
//         $this->generateKode();
//     }

//     private function hitungSkor()
//     {
//         if ($this->id_jenis_kendaraan == 3) {
//             return 5; // Tidak punya kendaraan
//         }

//         if ($this->id_jenis_kendaraan == 2) {
//             // Motor
//             if ($this->jumlah >= 3) {
//                 return 4;
//             } else {
//                 return 3;
//             }
//         }

//         if ($this->id_jenis_kendaraan == 1) {
//             return 1; // Mobil
//         }

//         return 5;
//     }

//     public function edit($id)
//     {
//         $data = Kendaraan::findOrFail($id);
//         $this->kode_kendaraan = $data->kode_kendaraan;
//         $this->kode_penerima = $data->kode_penerima;
//         $this->id_jenis_kendaraan = $data->id_jenis_kendaraan;
//         $this->jumlah = $data->jumlah;
//     }

//     public function delete($id)
//     {
//         Kendaraan::findOrFail($id)->delete();
//         session()->flash('message', 'Data kendaraan berhasil dihapus.');
//     }

//     public function resetInput()
//     {
//         $this->kode_penerima = '';
//         $this->id_jenis_kendaraan = '';
//         $this->jumlah = '';
//     }

//     private function generateKode()
//     {
//         $last = Kendaraan::orderBy('kode_kendaraan', 'desc')->first();
//         if (!$last) {
//             $this->kode_kendaraan = 'KEN-001';
//         } else {
//             $num = (int)substr($last->kode_kendaraan, 4) + 1;
//             $this->kode_kendaraan = 'KEN-' . str_pad($num, 3, '0', STR_PAD_LEFT);
//         }
//     }
// }


// namespace App\Livewire;

// use App\Models\Kendaraan;
// use App\Models\JenisKendaraan;
// use App\Models\PenerimaBantuan as Penerima;
// use Livewire\Component;
// use Livewire\Attributes\Layout;
// use Livewire\WithPagination;

// class KendaraanPage extends Component
// {
//     use WithPagination;

//     #[Layout('components.layouts.app')]

//     public $kode_kendaraan;
//     public $kode_penerima;
//     public $id_jenis_kendaraan;
//     public $jumlah;

//     protected $paginationTheme = 'bootstrap'; // ✅ Agar pagination sesuai Bootstrap

//     public function mount()
//     {
//         $this->generateKode();
//     }

//     public function render()
//     {
//         return view('livewire.kendaraan', [
//             'data_kendaraan' => Kendaraan::with(['penerima', 'jenisKendaraan'])->paginate(10),
//             'jenis_kendaraan' => JenisKendaraan::all(),
//             'data_penerima' => Penerima::all(),
//         ]);
//     }

//     public function simpan()
//     {
//         $this->validate([
//             'kode_kendaraan' => 'required',
//             'kode_penerima' => 'required',
//             'id_jenis_kendaraan' => 'required',
//             'jumlah' => 'required|integer',
//         ]);

//         Kendaraan::updateOrCreate(
//             ['kode_kendaraan' => $this->kode_kendaraan],
//             [
//                 'kode_penerima' => $this->kode_penerima,
//                 'id_jenis_kendaraan' => $this->id_jenis_kendaraan,
//                 'jumlah' => $this->jumlah,
//             ]
//         );

//         session()->flash('message', 'Data berhasil disimpan.');
//         $this->resetInput();
//         $this->generateKode();
//     }

//     public function edit($id)
//     {
//         $data = Kendaraan::find($id);
//         $this->kode_kendaraan = $data->kode_kendaraan;
//         $this->kode_penerima = $data->kode_penerima;
//         $this->id_jenis_kendaraan = $data->id_jenis_kendaraan;
//         $this->jumlah = $data->jumlah;
//     }

//     public function delete($id)
//     {
//         Kendaraan::find($id)->delete();
//         session()->flash('message', 'Data berhasil dihapus.');
//     }

//     public function resetInput()
//     {
//         $this->kode_penerima = '';
//         $this->id_jenis_kendaraan = '';
//         $this->jumlah = '';
//     }

//     private function generateKode()
//     {
//         $last = Kendaraan::orderBy('kode_kendaraan', 'desc')->first();
//         if (!$last) {
//             $this->kode_kendaraan = 'KEN-001';
//         } else {
//             $num = (int)substr($last->kode_kendaraan, 4) + 1;
//             $this->kode_kendaraan = 'KEN-' . str_pad($num, 3, '0', STR_PAD_LEFT);
//         }
//     }
// }
