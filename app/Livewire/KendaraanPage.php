<?php
//  Komponen Livewire untuk mengelola data kendaraan penerima bantuan
namespace App\Livewire;
use Livewire\Attributes\Layout;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Kendaraan;
use App\Models\JenisKendaraan;
use App\Models\PenerimaBantuan;
use App\Models\Penilaian;

class KendaraanPage extends Component
{
    use WithPagination;
    #[Layout('components.layouts.app')]

    //  Tema pagination
    protected $paginationTheme = 'bootstrap';

    //   Variabel yang akan di-bind dengan form
    public $kode_kendaraan;
    public $kode_penerima;
    public $id_jenis_kendaraan;
    public $jumlah;

    //   Inisialisasi kode kendaraan otomatis saat pertama load
    public function mount()
    {
        $this->generateKode();
    }

    //   Render halaman dan passing data ke blade
    public function render()
    {
        return view('livewire.kendaraan', [
            'data_kendaraan' => Kendaraan::with(['penerima', 'jenisKendaraan'])->paginate(10),
            'jenis_kendaraan' => JenisKendaraan::all(),
            'data_penerima' => PenerimaBantuan::all(),
        ]);
    }

    //   Fungsi menyimpan data kendaraan
    public function simpan()
    {
        $this->validate([
            'kode_kendaraan' => 'required',
            'kode_penerima' => 'required',
            'id_jenis_kendaraan' => 'required',
            'jumlah' => 'required|integer|min:0',
        ]);

        // ➕ Simpan atau update data kendaraan
        Kendaraan::updateOrCreate(
            ['kode_kendaraan' => $this->kode_kendaraan],
            [
                'kode_penerima' => $this->kode_penerima,
                'id_jenis_kendaraan' => $this->id_jenis_kendaraan,
                'jumlah' => $this->jumlah,
            ]
        );

        //   Hitung skor kendaraan untuk penilaian
        $skor = $this->hitungSkor();

        //   Update ke tabel penilaian
        Penilaian::updateOrCreate(
            ['kode_penerima' => $this->kode_penerima],
            [
                'skor_kendaraan' => $skor,
                'tanggal_penilaian' => now(),
            ]
        );

        //   Hitung ulang total dan status penerima berdasarkan SAW
        Penilaian::hitungSAWPerPenerima($this->kode_penerima);

        session()->flash('message', 'Data kendaraan berhasil disimpan.');

        $this->resetInput();
        $this->generateKode();
    }

    //   Hitung skor kendaraan
    private function hitungSkor()
    {
        if ($this->id_jenis_kendaraan == 3) {
            return 5; // ✅ Tidak memiliki kendaraan
        }
        if ($this->id_jenis_kendaraan == 2) {
            return ($this->jumlah >= 3) ? 4 : 3; // ✅ Motor
        }
        if ($this->id_jenis_kendaraan == 1) {
            return 1; // ✅ Mobil
        }
        return 5;
    }

    //   Fungsi edit
    public function edit($id)
    {
        $data = Kendaraan::findOrFail($id);
        $this->kode_kendaraan = $data->kode_kendaraan;
        $this->kode_penerima = $data->kode_penerima;
        $this->id_jenis_kendaraan = $data->id_jenis_kendaraan;
        $this->jumlah = $data->jumlah;
    }

    //   Fungsi hapus data
    public function delete($id)
    {
        $data = Kendaraan::findOrFail($id);
        $kode_penerima = $data->kode_penerima;

        $data->delete();

        Penilaian::updateOrCreate(
            ['kode_penerima' => $kode_penerima],
            [
                'skor_kendaraan' => 5, // reset skor ke tidak memiliki kendaraan
                'tanggal_penilaian' => now(),
            ]
        );

        Penilaian::hitungSAWPerPenerima($kode_penerima);

        session()->flash('message', 'Data kendaraan berhasil dihapus.');
    }

    //   Reset input form
    public function resetInput()
    {
        $this->kode_penerima = '';
        $this->id_jenis_kendaraan = '';
        $this->jumlah = '';
    }

    //   Generate kode kendaraan otomatis
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
