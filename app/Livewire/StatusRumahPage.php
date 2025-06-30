<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\StatusRumah;
use App\Models\TbStatusRumah;
use App\Models\PenerimaBantuan;
use App\Models\KondisiRumah;
use App\Models\JenisRumah;
use App\Models\Penilaian;

class StatusRumahPage extends Component
{
    use WithPagination;

    #[Layout('components.layouts.app')]
    protected $paginationTheme = 'bootstrap';

    public $kode_kepemilikan_rumah;
    public $kode_penerima;
    public $id_status_rumah;
    public $luas_rumah;
    public $id_kondisi;
    public $id_jenis;
    public $jumlah_penghuni;

    public function mount()
    {
        $this->generateKode();
    }

    public function render()
    {
        return view('livewire.status-rumah-page', [
            'data_rumah' => TbStatusRumah::with(['penerima', 'status', 'kondisi', 'jenis'])->latest()->paginate(10),
            'status_rumah' => StatusRumah::all(),
            'data_penerima' => PenerimaBantuan::all(),
            'kondisi_rumah' => KondisiRumah::all(),
            'jenis_rumah' => JenisRumah::all(),
        ]);
    }

    public function simpan()
    {
        $this->validate([
            'kode_kepemilikan_rumah' => 'required',
            'kode_penerima' => 'required',
            'id_status_rumah' => 'required',
            'luas_rumah' => 'required|integer',
            'id_kondisi' => 'required',
            'id_jenis' => 'required',
            'jumlah_penghuni' => 'nullable|integer',
        ]);

        TbStatusRumah::updateOrCreate(
            ['kode_kepemilikan_rumah' => $this->kode_kepemilikan_rumah],
            [
                'kode_penerima' => $this->kode_penerima,
                'id_status_rumah' => $this->id_status_rumah,
                'luas_rumah' => $this->luas_rumah,
                'id_kondisi' => $this->id_kondisi,
                'id_jenis' => $this->id_jenis,
                'jumlah_penghuni' => $this->jumlah_penghuni,
            ]
        );

        $skor = $this->hitungSkor();

        $penilaian = Penilaian::updateOrCreate(
            ['kode_penerima' => $this->kode_penerima],
            ['skor_rumah' => $skor, 'tanggal_penilaian' => now()]
        );

        // 🔥 Hitung total setiap kali ada perubahan
        // $penilaian->hitungTotalDanStatus();
        Penilaian::hitungSAWPerPenerima($this->kode_penerima);

        session()->flash('message', 'Data berhasil disimpan.');
        $this->resetInput();
        $this->generateKode();
    }


            private function hitungSkor()
        {
            $skor = 0;

            // 🔸 Status Rumah
            if ($this->id_status_rumah == 1) {
                $skor += 1; // Milik Sendiri
            } elseif ($this->id_status_rumah == 2) {
                $skor += 2; // Sewa
            } elseif ($this->id_status_rumah == 3) {
                $skor += 3; // Menumpang
            } elseif ($this->id_status_rumah == 4) {
                $skor += 4; // Kontrak
            }

            // 🔸 Jenis Rumah
            if ($this->id_jenis == 1) {
                $skor += 1; // Permanen
            } elseif ($this->id_jenis == 2) {
                $skor += 3; // Semi Permanen
            } elseif ($this->id_jenis == 3) {
                $skor += 5; // Non Permanen
            }

            // 🔸 Kondisi Rumah
            if ($this->id_kondisi == 1) {
                $skor += 1; // Baik
            } elseif ($this->id_kondisi == 2) {
                $skor += 3; // Sedang
            } elseif ($this->id_kondisi == 3) {
                $skor += 5; // Rusak
            }

            // 🔸 Luas Rumah
            if ($this->luas_rumah >= 70) {
                $skor += 1;
            } elseif ($this->luas_rumah >= 40) {
                $skor += 3;
            } else {
                $skor += 5;
            }

            // 🔸 Jumlah Penghuni
            if ($this->jumlah_penghuni >= 6) {
                $skor += 5;
            } elseif ($this->jumlah_penghuni >= 4) {
                $skor += 3;
            } elseif ($this->jumlah_penghuni >= 1) {
                $skor += 1;
            }

            return $skor;
        }


    public function edit($id)
    {
        $data = TbStatusRumah::findOrFail($id);
        $this->kode_kepemilikan_rumah = $data->kode_kepemilikan_rumah;
        $this->kode_penerima = $data->kode_penerima;
        $this->id_status_rumah = $data->id_status_rumah;
        $this->luas_rumah = $data->luas_rumah;
        $this->id_kondisi = $data->id_kondisi;
        $this->id_jenis = $data->id_jenis;
        $this->jumlah_penghuni = $data->jumlah_penghuni;
    }

    public function delete($id)
    {
        TbStatusRumah::findOrFail($id)->delete();
        session()->flash('message', 'Data berhasil dihapus.');
    }

    public function resetInput()
    {
        $this->kode_penerima = '';
        $this->id_status_rumah = '';
        $this->luas_rumah = '';
        $this->id_kondisi = '';
        $this->id_jenis = '';
        $this->jumlah_penghuni = '';
    }

    private function generateKode()
    {
        $last = TbStatusRumah::orderBy('kode_kepemilikan_rumah', 'desc')->first();
        if (!$last) {
            $this->kode_kepemilikan_rumah = 'SRM-001';
        } else {
            $num = (int)substr($last->kode_kepemilikan_rumah, 4) + 1;
            $this->kode_kepemilikan_rumah = 'SRM-' . str_pad($num, 3, '0', STR_PAD_LEFT);
        }
    }

    public function updating($property)
    {
        $this->resetPage();
    }
}

// namespace App\Livewire;

// use Livewire\Component;
// use Livewire\Attributes\Layout;
// use Livewire\WithPagination;
// use App\Models\StatusRumah;
// use App\Models\TbStatusRumah;
// use App\Models\PenerimaBantuan;
// use App\Models\KondisiRumah;
// use App\Models\JenisRumah;

// class StatusRumahPage extends Component
// {
//     use WithPagination;

//     #[Layout('components.layouts.app')]

//     protected $paginationTheme = 'bootstrap'; // ✅ Tambahkan agar pagination Bootstrap

//     public $kode_kepemilikan_rumah;
//     public $kode_penerima;
//     public $id_status_rumah;
//     public $luas_rumah;
//     public $id_kondisi;
//     public $id_jenis;
//     public $jumlah_penghuni;

//     public function mount()
//     {
//         $this->generateKode();
//     }

//     public function render()
//     {
//         return view('livewire.status-rumah-page', [
//             'data_rumah' => TbStatusRumah::with(['penerima', 'status', 'kondisi', 'jenis'])
//                 ->latest()->paginate(10),
//             'status_rumah' => StatusRumah::all(),
//             'data_penerima' => PenerimaBantuan::all(),
//             'kondisi_rumah' => KondisiRumah::all(),
//             'jenis_rumah' => JenisRumah::all(),
//         ]);
//     }

//     public function simpan()
//     {
//         $this->validate([
//             'kode_kepemilikan_rumah' => 'required',
//             'kode_penerima' => 'required',
//             'id_status_rumah' => 'required',
//             'luas_rumah' => 'required|integer',
//             'id_kondisi' => 'required',
//             'id_jenis' => 'required',
//             'jumlah_penghuni' => 'nullable|integer',
//         ]);

//         TbStatusRumah::updateOrCreate(
//             ['kode_kepemilikan_rumah' => $this->kode_kepemilikan_rumah],
//             [
//                 'kode_penerima' => $this->kode_penerima,
//                 'id_status_rumah' => $this->id_status_rumah,
//                 'luas_rumah' => $this->luas_rumah,
//                 'id_kondisi' => $this->id_kondisi,
//                 'id_jenis' => $this->id_jenis,
//                 'jumlah_penghuni' => $this->jumlah_penghuni,
//             ]
//         );

//         session()->flash('message', 'Data berhasil disimpan.');
//         $this->resetInput();
//         $this->generateKode();
//     }

//     public function edit($id)
//     {
//         $data = TbStatusRumah::findOrFail($id);
//         $this->kode_kepemilikan_rumah = $data->kode_kepemilikan_rumah;
//         $this->kode_penerima = $data->kode_penerima;
//         $this->id_status_rumah = $data->id_status_rumah;
//         $this->luas_rumah = $data->luas_rumah;
//         $this->id_kondisi = $data->id_kondisi;
//         $this->id_jenis = $data->id_jenis;
//         $this->jumlah_penghuni = $data->jumlah_penghuni;
//     }

//     public function delete($id)
//     {
//         TbStatusRumah::findOrFail($id)->delete();
//         session()->flash('message', 'Data berhasil dihapus.');
//     }

//     public function resetInput()
//     {
//         $this->kode_penerima = '';
//         $this->id_status_rumah = '';
//         $this->luas_rumah = '';
//         $this->id_kondisi = '';
//         $this->id_jenis = '';
//         $this->jumlah_penghuni = '';
//     }

//     private function generateKode()
//     {
//         $last = TbStatusRumah::orderBy('kode_kepemilikan_rumah', 'desc')->first();
//         if (!$last) {
//             $this->kode_kepemilikan_rumah = 'SRM-001';
//         } else {
//             $num = (int)substr($last->kode_kepemilikan_rumah, 4) + 1;
//             $this->kode_kepemilikan_rumah = 'SRM-' . str_pad($num, 3, '0', STR_PAD_LEFT);
//         }
//     }

//     public function updating($property)
//     {
//         $this->resetPage();
//     }
// }
