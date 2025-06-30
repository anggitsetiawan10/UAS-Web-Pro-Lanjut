<?php
namespace App\Http\Controllers;

use App\Models\Penilaian;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PenilaianController extends Controller
{
    public function exportPDF()
    {
        $data = Penilaian::with('penerima', 'status')->get();
        $pdf = Pdf::loadView('penilaian.pdf', compact('data'));

        return $pdf->download('laporan-penilaian.pdf');
    }
}
