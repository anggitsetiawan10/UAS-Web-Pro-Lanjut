<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Auth;
use App\Livewire\PenerimaBantuanPage;
use App\Livewire\Kendaraan;
use App\Livewire\KendaraanPage;
use App\Livewire\StatusRumahPage;
use App\Livewire\SearchPageNav;
use App\Http\Controllers\PenilaianController;


Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->middleware('auth');
Route::get('/login',Login::class)
        ->middleware('guest')
        ->name('login');

Route::get('/dashboard', Dashboard::class)->middleware('auth')->name('dashboard');

Route::get('/logout', function(Request $request){
    Auth::logout();
    $request->session()->flush();
    return redirect('/');
})->name('logout');

Route::get('/penerima-bantuan', PenerimaBantuanPage::class)
    ->middleware('auth')
    ->name('penerima-bantuan');

Route::get('/kendaraan', KendaraanPage::class)
->middleware('auth')
->name('kendaraan');

Route::get('/status-rumah', StatusRumahPage::class)
->middleware('auth')
->name('status-rumah');
use App\Livewire\PendapatanPage;
use App\Livewire\PenilaianPage;
use App\Livewire\SearchPage;
use App\Livewire\TanggunganPage;

Route::get('/pendapatan', PendapatanPage::class)
->middleware('auth')
->name('pendapatan');

Route::get('/tanggungan', TanggunganPage::class)
->middleware('auth')
->name('tanggungan');

Route::get('/penilaian', PenilaianPage::class)
->middleware('auth')
->name('penilaian');

Route::get('/search', App\Livewire\SearchPageNav::class)
    ->middleware('auth')
    ->name('search');


Route::get('/export-pdf', [PenilaianController::class, 'exportPDF'])->name('export.pdf');
