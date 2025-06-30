<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PenerimaBantuan;
use App\Models\Penilaian;
use Livewire\Attributes\Layout;

class Dashboard extends Component
{
    #[Layout('components.layouts.app')]

    public function render()
    {
        $total = PenerimaBantuan::count();
        $totalLaki = PenerimaBantuan::where('jenis_kelamin', 'L')->count();
        $totalPerempuan = PenerimaBantuan::where('jenis_kelamin', 'P')->count();

        $surveiPerBulan = PenerimaBantuan::selectRaw('MONTH(tanggal_survei) as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $penilaian = Penilaian::with(['penerima', 'status'])
            ->orderByDesc('skor_total')
            ->get();

        // Hitung jumlah layak dan tidak layak
        $layak = Penilaian::where('kode_status', 2)->count();
        $tidakLayak = Penilaian::where('kode_status', 1)->count();

        // Total semua status penilaian
        $totalStatus = $layak + $tidakLayak;

        return view('livewire.dashboard', [
            'total' => $total,
            'totalLaki' => $totalLaki,
            'totalPerempuan' => $totalPerempuan,
            'surveiPerBulan' => $surveiPerBulan,
            'penilaian' => $penilaian,
            'layak' => $layak,
            'tidakLayak' => $tidakLayak,
            'totalStatus' => $totalStatus, // ✅ ditambahkan total status
        ]);
    }

}



// namespace App\Livewire;

// use Livewire\Component;
// use Livewire\Attributes\Layout;

// class Dashboard extends Component
// {
//     #[Layout('components.layouts.app')]

//     public function render()
//     {
//         return view('livewire.dashboard');
//     }
// }
