<?php

namespace App\Livewire;

use App\Models\TbTanggungan;
use App\Models\PenerimaBantuan;
use App\Models\Penilaian;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class TanggunganPage extends Component
{
    use WithPagination;

    #[Layout('components.layouts.app')]

    public $kode_tanggungan;
    public $kode_penerima;
    public $jumlah_anak;
    public $anak_sekolah;
    public $anak_belum_sekolah;
    public $jumlah_tanggungan_lain;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->generateKode();
    }

    public function render()
    {
        return view('livewire.tanggungan-page', [
            'data_tanggungan' => TbTanggungan::with('penerima')->paginate(10),
            'data_penerima' => PenerimaBantuan::all(),
        ]);
    }

    public function simpan()
    {
        $this->validate([
            'kode_penerima' => 'required',
            'jumlah_anak' => 'required|integer',
            'anak_sekolah' => 'required|integer',
            'anak_belum_sekolah' => 'required|integer',
            'jumlah_tanggungan_lain' => 'required|integer',
        ]);

        TbTanggungan::updateOrCreate(
            ['kode_tanggungan' => $this->kode_tanggungan],
            [
                'kode_penerima' => $this->kode_penerima,
                'jumlah_anak' => $this->jumlah_anak,
                'anak_sekolah' => $this->anak_sekolah,
                'anak_belum_sekolah' => $this->anak_belum_sekolah,
                'jumlah_tanggungan_lain' => $this->jumlah_tanggungan_lain,
            ]
        );

        // ✅ Hitung Skor Tanggungan
        $skor = $this->hitungSkorTanggungan();

        $penilaian = Penilaian::updateOrCreate(
            ['kode_penerima' => $this->kode_penerima],
            ['skor_tanggungan' => $skor, 'tanggal_penilaian' => now()]
        );

        // $penilaian->hitungTotalDanStatus();
        Penilaian::hitungSAWPerPenerima($this->kode_penerima);

        session()->flash('message', 'Data berhasil disimpan.');
        $this->resetInput();
        $this->generateKode();
    }

    private function hitungSkorTanggungan()
    {
        // ✅ Hitung total tanggungan (anak + tanggungan lain)
        $totalTanggungan = (int)$this->jumlah_anak + (int)$this->jumlah_tanggungan_lain;

        // ✅ Anak sekolah bisa dijadikan tambahan jika mau
        // Misal: setiap anak sekolah tambahkan +1 ke total tanggungan
        // Uncomment jika ingin
        $totalTanggungan += (int)$this->anak_sekolah;

        // ✅ Skor berdasarkan total
        if ($totalTanggungan >= 5) {
            return 5;
        } elseif ($totalTanggungan >= 3) {
            return 3;
        } else {
            return 1;
        }
    }

    public function edit($id)
    {
        $data = TbTanggungan::findOrFail($id);
        $this->kode_tanggungan = $data->kode_tanggungan;
        $this->kode_penerima = $data->kode_penerima;
        $this->jumlah_anak = $data->jumlah_anak;
        $this->anak_sekolah = $data->anak_sekolah;
        $this->anak_belum_sekolah = $data->anak_belum_sekolah;
        $this->jumlah_tanggungan_lain = $data->jumlah_tanggungan_lain;
    }

    public function delete($id)
    {
        TbTanggungan::findOrFail($id)->delete();
        session()->flash('message', 'Data berhasil dihapus.');
    }

    public function resetInput()
    {
        $this->kode_penerima = '';
        $this->jumlah_anak = '';
        $this->anak_sekolah = '';
        $this->anak_belum_sekolah = '';
        $this->jumlah_tanggungan_lain = '';
    }

    private function generateKode()
    {
        $last = TbTanggungan::orderBy('kode_tanggungan', 'desc')->first();
        if (!$last) {
            $this->kode_tanggungan = 'TGN-001';
        } else {
            $num = (int)substr($last->kode_tanggungan, 4) + 1;
            $this->kode_tanggungan = 'TGN-' . str_pad($num, 3, '0', STR_PAD_LEFT);
        }
    }
}



// namespace App\Livewire;

// use Livewire\Component;
// use App\Models\TbTanggungan;
// use App\Models\PenerimaBantuan;
// use Livewire\WithPagination;
// use Livewire\Attributes\Layout;

// class TanggunganPage extends Component
// {
//     use WithPagination;

//     #[Layout('components.layouts.app')]

//     public $kode_tanggungan, $kode_penerima, $jumlah_anak, $anak_sekolah, $anak_belum_sekolah, $jumlah_tanggungan_lain;

//     protected $paginationTheme = 'bootstrap'; // ✅ Agar tampilan pagination sesuai Bootstrap

//     public function mount()
//     {
//         $this->generateKode();
//     }

//     public function render()
//     {
//         return view('livewire.tanggungan-page', [
//             'data_tanggungan' => TbTanggungan::with('penerima')->paginate(10), // ✅ Pagination aktif
//             'data_penerima' => PenerimaBantuan::all(),
//         ]);
//     }

//     public function simpan()
//     {
//         $this->validate([
//             'kode_tanggungan' => 'required',
//             'kode_penerima' => 'required',
//             'jumlah_anak' => 'required|integer',
//             'anak_sekolah' => 'required|integer',
//             'anak_belum_sekolah' => 'required|integer',
//             'jumlah_tanggungan_lain' => 'required|integer',
//         ]);

//         TbTanggungan::updateOrCreate(
//             ['kode_tanggungan' => $this->kode_tanggungan],
//             [
//                 'kode_penerima' => $this->kode_penerima,
//                 'jumlah_anak' => $this->jumlah_anak,
//                 'anak_sekolah' => $this->anak_sekolah,
//                 'anak_belum_sekolah' => $this->anak_belum_sekolah,
//                 'jumlah_tanggungan_lain' => $this->jumlah_tanggungan_lain,
//             ]
//         );

//         session()->flash('message', 'Data berhasil disimpan.');
//         $this->resetInput();
//         $this->generateKode();
//     }

//     public function edit($id)
//     {
//         $data = TbTanggungan::findOrFail($id);
//         $this->kode_tanggungan = $data->kode_tanggungan;
//         $this->kode_penerima = $data->kode_penerima;
//         $this->jumlah_anak = $data->jumlah_anak;
//         $this->anak_sekolah = $data->anak_sekolah;
//         $this->anak_belum_sekolah = $data->anak_belum_sekolah;
//         $this->jumlah_tanggungan_lain = $data->jumlah_tanggungan_lain;
//     }

//     public function delete($id)
//     {
//         TbTanggungan::findOrFail($id)->delete();
//         session()->flash('message', 'Data berhasil dihapus.');
//     }

//     public function resetInput()
//     {
//         $this->kode_penerima = '';
//         $this->jumlah_anak = '';
//         $this->anak_sekolah = '';
//         $this->anak_belum_sekolah = '';
//         $this->jumlah_tanggungan_lain = '';
//     }

//     private function generateKode()
//     {
//         $last = TbTanggungan::orderBy('kode_tanggungan', 'desc')->first();
//         if (!$last) {
//             $this->kode_tanggungan = 'TGN-001';
//         } else {
//             $num = (int)substr($last->kode_tanggungan, 4) + 1;
//             $this->kode_tanggungan = 'TGN-' . str_pad($num, 3, '0', STR_PAD_LEFT);
//         }
//     }
// }
