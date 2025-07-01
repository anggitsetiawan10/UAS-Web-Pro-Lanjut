<?php

namespace App\Livewire;

use App\Models\JenisPekerjaan;
use App\Models\TbPendapatan;
use App\Models\PenerimaBantuan;
use App\Models\Penilaian;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

class PendapatanPage extends Component
{
    use WithPagination;

    #[Layout('components.layouts.app')]

    // === Properti ===
    public $kode_pendapatan;
    public $kode_penerima;
    public $kode_profesi;
    public $pendapatan_bulanan;

    protected $paginationTheme = 'bootstrap';

    // === Lifecycle ===
    public function mount()
    {
        $this->generateKode();
    }

    // === Generate Kode Otomatis ===
    private function generateKode()
    {
        $last = TbPendapatan::orderBy('kode_pendapatan', 'desc')->first();
        if (!$last) {
            $this->kode_pendapatan = 'PND-001';
        } else {
            $num = (int)substr($last->kode_pendapatan, 4) + 1;
            $this->kode_pendapatan = 'PND-' . str_pad($num, 3, '0', STR_PAD_LEFT);
        }
    }

    // === CRUD ===
    public function simpan()
    {
        $this->validate([
            'kode_penerima' => 'required',
            'kode_profesi' => 'required',
            'pendapatan_bulanan' => 'required|integer',
        ]);

        TbPendapatan::updateOrCreate(
            ['kode_pendapatan' => $this->kode_pendapatan],
            [
                'kode_penerima' => $this->kode_penerima,
                'kode_profesi' => $this->kode_profesi,
                'pendapatan_bulanan' => $this->pendapatan_bulanan,
            ]
        );

        // Hitung dan simpan skor ke tabel penilaian
        $skor = $this->hitungSkorPendapatan();

        Penilaian::updateOrCreate(
            ['kode_penerima' => $this->kode_penerima],
            ['skor_pendapatan' => $skor, 'tanggal_penilaian' => now()]
        );

        Penilaian::hitungSAWPerPenerima($this->kode_penerima);

        session()->flash('message', 'Data berhasil disimpan.');
        $this->resetInput();
        $this->generateKode();
    }

    public function edit($id)
    {
        $data = TbPendapatan::findOrFail($id);
        $this->kode_pendapatan = $data->kode_pendapatan;
        $this->kode_penerima = $data->kode_penerima;
        $this->kode_profesi = $data->kode_profesi;
        $this->pendapatan_bulanan = $data->pendapatan_bulanan;
    }

    public function delete($id)
    {
        TbPendapatan::findOrFail($id)->delete();
        session()->flash('message', 'Data berhasil dihapus.');
    }

    public function resetInput()
    {
        $this->kode_penerima = '';
        $this->kode_profesi = '';
        $this->pendapatan_bulanan = '';
    }

    // === Hitung Skor ===
    private function hitungSkorPendapatan()
    {
        $skorPendapatan = 1;
        if ($this->pendapatan_bulanan < 1000000) {
            $skorPendapatan = 5;
        } elseif ($this->pendapatan_bulanan <= 2000000) {
            $skorPendapatan = 3;
        }

        $profesi = JenisPekerjaan::find($this->kode_profesi);
        $skorProfesi = 1;

        if ($profesi) {
            $nama = strtolower($profesi->nama_pekerjaan);
            if (in_array($nama, ['buruh', 'petani', 'nelayan'])) {
                $skorProfesi = 5;
            } elseif ($nama === 'pedagang') {
                $skorProfesi = 4;
            } elseif (in_array($nama, ['karyawan', 'guru'])) {
                $skorProfesi = 3;
            } elseif (in_array($nama, ['swasta', 'pns'])) {
                $skorProfesi = 2;
            }
        }

        return $skorPendapatan + $skorProfesi;
    }

    // === Render ===
    public function render()
    {
        return view('livewire.pendapatan-page', [
            'data_pendapatan' => TbPendapatan::with(['penerima', 'profesi'])->paginate(10),
            'data_penerima' => PenerimaBantuan::all(),
            'data_profesi' => JenisPekerjaan::all(),
        ]);
    }
}
