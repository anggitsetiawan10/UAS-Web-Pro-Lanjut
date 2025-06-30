<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PenerimaBantuan;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;

class PenerimaBantuanPage extends Component
{
    use WithPagination;

    #[Layout('components.layouts.app')]

    // Form fields
    public $kode_penerima, $nik, $nama, $alamat, $tanggal_lahir, $jenis_kelamin, $kontak, $tanggal_survei;
    public $searchInput = '';
    public $search = '';
    public $editMode = false;

    protected $paginationTheme = 'bootstrap';

    protected $updatesQueryString = ['search'];

    public function mount()
    {
        $this->kode_penerima = $this->generateKode();
    }

    public function generateKode()
    {
        $prefix = 'PNR-';
        $tanggal = now()->format('Ymd');
        $random = strtoupper(Str::random(4));
        $kode = $prefix . $tanggal . '-' . $random;

        while (PenerimaBantuan::where('kode_penerima', $kode)->exists()) {
            $random = strtoupper(Str::random(4));
            $kode = $prefix . $tanggal . '-' . $random;
        }

        return $kode;
    }
    public function doSearch()
    {
        $this->search = $this->searchInput;
    }


    public function render()
    {
        $data = PenerimaBantuan::query()
            ->when($this->search, function($query) {
                $query->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('nik', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.penerima-bantuan', compact('data'));
    }

    public function resetForm()
    {
        $this->kode_penerima = $this->generateKode();
        $this->nik = '';
        $this->nama = '';
        $this->alamat = '';
        $this->tanggal_lahir = '';
        $this->jenis_kelamin = '';
        $this->kontak = '';
        $this->tanggal_survei = '';
        $this->editMode = false;
    }

    public function resetSearch()
    {
        $this->search = '';
        $this->searchInput = '';
    }


    public function save()
    {
        $this->validate([
            'kode_penerima' => 'required|unique:penerima_bantuan,kode_penerima',
            'nik' => 'required|unique:penerima_bantuan,nik',
            'nama' => 'required',
            'alamat' => 'required',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'kontak' => 'required',
            'tanggal_survei' => 'required|date',
        ]);

        PenerimaBantuan::create([
            'kode_penerima' => $this->kode_penerima,
            'nik' => $this->nik,
            'nama' => $this->nama,
            'alamat' => $this->alamat,
            'tanggal_lahir' => $this->tanggal_lahir,
            'jenis_kelamin' => $this->jenis_kelamin,
            'kontak' => $this->kontak,
            'tanggal_survei' => $this->tanggal_survei,
        ]);

        session()->flash('success', 'Data berhasil ditambahkan');
        $this->resetForm();
    }

    public function edit($id)
    {
        $data = PenerimaBantuan::findOrFail($id);
        $this->kode_penerima = $data->kode_penerima;
        $this->nik = $data->nik;
        $this->nama = $data->nama;
        $this->alamat = $data->alamat;
        $this->tanggal_lahir = $data->tanggal_lahir;
        $this->jenis_kelamin = $data->jenis_kelamin;
        $this->kontak = $data->kontak;
        $this->tanggal_survei = $data->tanggal_survei;

        $this->editMode = true;
    }

    public function update()
    {
        $this->validate([
            'nik' => 'required',
            'nama' => 'required',
            'alamat' => 'required',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'kontak' => 'required',
            'tanggal_survei' => 'required|date',
        ]);

        $data = PenerimaBantuan::findOrFail($this->kode_penerima);

        $data->update([
            'nik' => $this->nik,
            'nama' => $this->nama,
            'alamat' => $this->alamat,
            'tanggal_lahir' => $this->tanggal_lahir,
            'jenis_kelamin' => $this->jenis_kelamin,
            'kontak' => $this->kontak,
            'tanggal_survei' => $this->tanggal_survei,
        ]);

        session()->flash('success', 'Data berhasil diupdate');
        $this->resetForm();
    }

    public function delete($id)
    {
        $data = PenerimaBantuan::findOrFail($id);
        $data->delete();

        session()->flash('success', 'Data berhasil dihapus');
    }
}
