<?php

namespace App\Livewire;

use App\Models\Penilaian;
use App\Models\PenerimaBantuan;
use App\Models\StatusKelayakan;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class PenilaianPage extends Component
{
    use WithPagination;

    #[Layout('components.layouts.app')]
    protected $paginationTheme = 'bootstrap';

    public $kode_penerima;
    public $skor_rumah = 0;
    public $skor_kendaraan = 0;
    public $skor_pendapatan = 0;
    public $skor_tanggungan = 0;
    public $catatan;
    public $tanggal_penilaian;

    public $editMode = false;
    public $searchInput = '';
    public $search = '';

    // 🔍 Search
    public function doSearch()
    {
        $this->search = $this->searchInput;
    }

    public function render()
    {
        $data = Penilaian::with(['penerima', 'status'])
            ->when($this->search, function ($query) {
                $query->whereHas('penerima', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('tanggal_penilaian', 'desc')
            ->paginate(10);

        return view('livewire.penilaian-page', [
            'data' => $data,
            'data_penerima' => PenerimaBantuan::all(),
            'data_status' => StatusKelayakan::all(),
        ]);
    }

    // 🔄 Reset form
    public function resetForm()
    {
        $this->kode_penerima = '';
        $this->skor_rumah = 0;
        $this->skor_kendaraan = 0;
        $this->skor_pendapatan = 0;
        $this->skor_tanggungan = 0;
        $this->catatan = '';
        $this->tanggal_penilaian = '';
        $this->editMode = false;
    }

    // 💾 Simpan data baru
    public function save()
    {
        $this->validate([
            'kode_penerima' => 'required',
            'skor_rumah' => 'required|integer',
            'skor_kendaraan' => 'required|integer',
            'skor_pendapatan' => 'required|integer',
            'skor_tanggungan' => 'required|integer',
            'tanggal_penilaian' => 'required|date',
        ]);

        Penilaian::updateOrCreate(
            ['kode_penerima' => $this->kode_penerima],
            [
                'skor_rumah' => $this->skor_rumah,
                'skor_kendaraan' => $this->skor_kendaraan,
                'skor_pendapatan' => $this->skor_pendapatan,
                'skor_tanggungan' => $this->skor_tanggungan,
                'catatan' => $this->catatan,
                'tanggal_penilaian' => $this->tanggal_penilaian,
            ]
        );

        //  Hitung ulang dengan SAW setelah simpan
        Penilaian::hitungSAW();

        session()->flash('success', 'Data berhasil disimpan.');
        $this->resetForm();
    }

    //  Edit data
    public function edit($kode_penerima)
    {
        $data = Penilaian::findOrFail($kode_penerima);

        $this->kode_penerima = $data->kode_penerima;
        $this->skor_rumah = $data->skor_rumah;
        $this->skor_kendaraan = $data->skor_kendaraan;
        $this->skor_pendapatan = $data->skor_pendapatan;
        $this->skor_tanggungan = $data->skor_tanggungan;
        $this->catatan = $data->catatan;
        $this->tanggal_penilaian = $data->tanggal_penilaian;

        $this->editMode = true;
    }

// 🔥 Update data
    public function update()
    {
        $this->validate([
            'kode_penerima' => 'required',
            'skor_rumah' => 'required|integer',
            'skor_kendaraan' => 'required|integer',
            'skor_pendapatan' => 'required|integer',
            'skor_tanggungan' => 'required|integer',
            'tanggal_penilaian' => 'required|date',
        ]);

        $data = Penilaian::where('kode_penerima', $this->kode_penerima)->firstOrFail();

        $data->update([
            'skor_rumah' => $this->skor_rumah,
            'skor_kendaraan' => $this->skor_kendaraan,
            'skor_pendapatan' => $this->skor_pendapatan,
            'skor_tanggungan' => $this->skor_tanggungan,
            'catatan' => $this->catatan,
            'tanggal_penilaian' => $this->tanggal_penilaian,
        ]);

        // 🔥 Hitung ulang dengan SAW setelah update
        Penilaian::hitungSAW();

        session()->flash('success', 'Data berhasil diupdate.');
        $this->resetForm();
    }

    // ❌ Hapus data
    public function delete($id)
    {
        $data = Penilaian::findOrFail($id);
        $data->delete();

        session()->flash('success', 'Data berhasil dihapus.');
    }

    // 🔁 Reset pencarian
    public function resetSearch()
    {
        $this->searchInput = '';
        $this->search = '';
    }
    public function refreshSAW()
    {
        Penilaian::hitungSAW();
        session()->flash('success', 'Perhitungan SAW berhasil diperbarui.');
    }

}

// namespace App\Livewire;

// use App\Models\Penilaian;
// use App\Models\PenerimaBantuan;
// use App\Models\StatusKelayakan;
// use Livewire\Component;
// use Livewire\WithPagination;
// use Livewire\Attributes\Layout;

// class PenilaianPage extends Component
// {
//     use WithPagination;

//     #[Layout('components.layouts.app')]
//     protected $paginationTheme = 'bootstrap';

//     public $kode_penerima;
//     public $skor_rumah = 0;
//     public $skor_kendaraan = 0;
//     public $skor_pendapatan = 0;
//     public $skor_tanggungan = 0;
//     public $skor_total = 0;
//     public $kode_status;
//     public $catatan;
//     public $tanggal_penilaian;

//     public $editMode = false;
//     public $searchInput = '';
//     public $search = '';

//     // 🔍 Search
//     public function doSearch()
//     {
//         $this->search = $this->searchInput;
//     }

//     public function render()
//     {
//         $data = Penilaian::with(['penerima', 'status'])
//             ->when($this->search, function ($query) {
//                 $query->whereHas('penerima', function ($q) {
//                     $q->where('nama', 'like', '%' . $this->search . '%');
//                 });
//             })
//             ->orderBy('tanggal_penilaian', 'desc')
//             ->paginate(10);

//         return view('livewire.penilaian-page', [
//             'data' => $data,
//             'data_penerima' => PenerimaBantuan::all(),
//             'data_status' => StatusKelayakan::all(),
//         ]);
//     }

//     // 🔥 Hitung total skor dan status otomatis
//     public function hitungSkorTotal()
//     {
//         $hasil = Penilaian::hitungTotalDanStatusManual(
//             $this->skor_rumah,
//             $this->skor_kendaraan,
//             $this->skor_pendapatan,
//             $this->skor_tanggungan
//         );

//         $this->skor_total = $hasil['total'];
//         $this->kode_status = $hasil['status'];
//     }

//     // 🔄 Reset form
//     public function resetForm()
//     {
//         $this->kode_penerima = '';
//         $this->skor_rumah = 0;
//         $this->skor_kendaraan = 0;
//         $this->skor_pendapatan = 0;
//         $this->skor_tanggungan = 0;
//         $this->skor_total = 0;
//         $this->kode_status = '';
//         $this->catatan = '';
//         $this->tanggal_penilaian = '';
//         $this->editMode = false;
//     }

//     // 💾 Simpan data baru
//     public function save()
//     {
//         $this->validate([
//             'kode_penerima' => 'required',
//             'skor_rumah' => 'required|integer',
//             'skor_kendaraan' => 'required|integer',
//             'skor_pendapatan' => 'required|integer',
//             'skor_tanggungan' => 'required|integer',
//             'tanggal_penilaian' => 'required|date',
//         ]);

//         $this->hitungSkorTotal();

//         Penilaian::updateOrCreate(
//             ['kode_penerima' => $this->kode_penerima],
//             [
//                 'skor_rumah' => $this->skor_rumah,
//                 'skor_kendaraan' => $this->skor_kendaraan,
//                 'skor_pendapatan' => $this->skor_pendapatan,
//                 'skor_tanggungan' => $this->skor_tanggungan,
//                 'skor_total' => $this->skor_total,
//                 'kode_status' => $this->kode_status,
//                 'catatan' => $this->catatan,
//                 'tanggal_penilaian' => $this->tanggal_penilaian,
//             ]
//         );

//         session()->flash('success', 'Data berhasil disimpan.');
//         $this->resetForm();
//     }

//     // ✏️ Edit data
//     public function edit($id)
//     {
//         $data = Penilaian::findOrFail($id);

//         $this->kode_penerima = $data->kode_penerima;
//         $this->skor_rumah = $data->skor_rumah;
//         $this->skor_kendaraan = $data->skor_kendaraan;
//         $this->skor_pendapatan = $data->skor_pendapatan;
//         $this->skor_tanggungan = $data->skor_tanggungan;
//         $this->skor_total = $data->skor_total;
//         $this->kode_status = $data->kode_status;
//         $this->catatan = $data->catatan;
//         $this->tanggal_penilaian = $data->tanggal_penilaian;

//         $this->editMode = true;
//     }

//     // 🔄 Update data
//     public function update()
//     {
//         $this->validate([
//             'kode_penerima' => 'required',
//             'skor_rumah' => 'required|integer',
//             'skor_kendaraan' => 'required|integer',
//             'skor_pendapatan' => 'required|integer',
//             'skor_tanggungan' => 'required|integer',
//             'tanggal_penilaian' => 'required|date',
//         ]);

//         $this->hitungSkorTotal();

//         $data = Penilaian::where('kode_penerima', $this->kode_penerima)->firstOrFail();

//         $data->update([
//             'skor_rumah' => $this->skor_rumah,
//             'skor_kendaraan' => $this->skor_kendaraan,
//             'skor_pendapatan' => $this->skor_pendapatan,
//             'skor_tanggungan' => $this->skor_tanggungan,
//             'skor_total' => $this->skor_total,
//             'kode_status' => $this->kode_status,
//             'catatan' => $this->catatan,
//             'tanggal_penilaian' => $this->tanggal_penilaian,
//         ]);

//         session()->flash('success', 'Data berhasil diupdate.');
//         $this->resetForm();
//     }

//     // ❌ Hapus data
//     public function delete($id)
//     {
//         $data = Penilaian::findOrFail($id);
//         $data->delete();

//         session()->flash('success', 'Data berhasil dihapus.');
//     }

//     // 🔁 Reset pencarian
//     public function resetSearch()
//     {
//         $this->searchInput = '';
//         $this->search = '';
//     }
// }



// namespace App\Livewire;

// use App\Models\Penilaian;
// use App\Models\PenerimaBantuan;
// use App\Models\StatusKelayakan;
// use Livewire\Component;
// use Livewire\WithPagination;
// use Livewire\Attributes\Layout;
// use Illuminate\Support\Str;

// class PenilaianPage extends Component
// {
//     use WithPagination;

//     #[Layout('components.layouts.app')]

//     public $kode_penilaian, $kode_penerima, $skor_rumah, $skor_kendaraan, $skor_pendapatan, $skor_tanggungan, $skor_total, $kode_status, $catatan, $tanggal_penilaian;
//     public $editMode = false;
//     public $searchInput = '';
//     public $search = '';

//     protected $paginationTheme = 'bootstrap';

//     public function mount()
//     {
//         $this->kode_penilaian = $this->generateKode();
//     }

//     public function generateKode()
//     {
//         $prefix = 'PNL-';
//         $tanggal = now()->format('Ymd');
//         $random = strtoupper(Str::random(4));
//         $kode = $prefix . $tanggal . '-' . $random;

//         while (Penilaian::where('kode_penilaian', $kode)->exists()) {
//             $random = strtoupper(Str::random(4));
//             $kode = $prefix . $tanggal . '-' . $random;
//         }

//         return $kode;
//     }

//     public function doSearch()
//     {
//         $this->search = $this->searchInput;
//     }

//     public function render()
//     {
//         $data = Penilaian::with(['penerima', 'status'])
//             ->when($this->search, function ($query) {
//                 $query->whereHas('penerima', function ($q) {
//                     $q->where('nama', 'like', '%' . $this->search . '%');
//                 });
//             })
//             ->orderBy('tanggal_penilaian', 'desc')
//             ->paginate(10);

//         return view('livewire.penilaian-page', [
//             'data' => $data,
//             'data_penerima' => PenerimaBantuan::all(),
//             'data_status' => StatusKelayakan::all(),
//         ]);
//     }

//     public function resetForm()
//     {
//         $this->kode_penilaian = $this->generateKode();
//         $this->kode_penerima = '';
//         $this->skor_rumah = '';
//         $this->skor_kendaraan = '';
//         $this->skor_pendapatan = '';
//         $this->skor_tanggungan = '';
//         $this->skor_total = '';
//         $this->kode_status = '';
//         $this->catatan = '';
//         $this->tanggal_penilaian = '';
//         $this->editMode = false;
//     }

//     public function save()
//     {
//         $this->validate([
//             'kode_penerima' => 'required',
//             'skor_rumah' => 'required|integer',
//             'skor_kendaraan' => 'required|integer',
//             'skor_pendapatan' => 'required|integer',
//             'skor_tanggungan' => 'required|integer',
//             'skor_total' => 'required|integer',
//             'kode_status' => 'required',
//             'tanggal_penilaian' => 'required|date',
//         ]);

//         Penilaian::create([
//             'kode_penilaian' => $this->kode_penilaian,
//             'kode_penerima' => $this->kode_penerima,
//             'skor_rumah' => $this->skor_rumah,
//             'skor_kendaraan' => $this->skor_kendaraan,
//             'skor_pendapatan' => $this->skor_pendapatan,
//             'skor_tanggungan' => $this->skor_tanggungan,
//             'skor_total' => $this->skor_total,
//             'kode_status' => $this->kode_status,
//             'catatan' => $this->catatan,
//             'tanggal_penilaian' => $this->tanggal_penilaian,
//         ]);

//         session()->flash('success', 'Data berhasil ditambahkan');
//         $this->resetForm();
//     }

//     public function edit($id)
//     {
//         $data = Penilaian::findOrFail($id);
//         $this->kode_penilaian = $data->kode_penilaian;
//         $this->kode_penerima = $data->kode_penerima;
//         $this->skor_rumah = $data->skor_rumah;
//         $this->skor_kendaraan = $data->skor_kendaraan;
//         $this->skor_pendapatan = $data->skor_pendapatan;
//         $this->skor_tanggungan = $data->skor_tanggungan;
//         $this->skor_total = $data->skor_total;
//         $this->kode_status = $data->kode_status;
//         $this->catatan = $data->catatan;
//         $this->tanggal_penilaian = $data->tanggal_penilaian;

//         $this->editMode = true;
//     }

//     public function update()
//     {
//         $this->validate([
//             'skor_rumah' => 'required|integer',
//             'skor_kendaraan' => 'required|integer',
//             'skor_pendapatan' => 'required|integer',
//             'skor_tanggungan' => 'required|integer',
//             'skor_total' => 'required|integer',
//             'kode_status' => 'required',
//             'tanggal_penilaian' => 'required|date',
//         ]);

//         $data = Penilaian::findOrFail($this->kode_penilaian);

//         $data->update([
//             'kode_penerima' => $this->kode_penerima,
//             'skor_rumah' => $this->skor_rumah,
//             'skor_kendaraan' => $this->skor_kendaraan,
//             'skor_pendapatan' => $this->skor_pendapatan,
//             'skor_tanggungan' => $this->skor_tanggungan,
//             'skor_total' => $this->skor_total,
//             'kode_status' => $this->kode_status,
//             'catatan' => $this->catatan,
//             'tanggal_penilaian' => $this->tanggal_penilaian,
//         ]);

//         session()->flash('success', 'Data berhasil diupdate');
//         $this->resetForm();
//     }

//     public function delete($id)
//     {
//         $data = Penilaian::findOrFail($id);
//         $data->delete();

//         session()->flash('success', 'Data berhasil dihapus');
//     }
// }
