<?php
use App\Http\Controllers\AlternatifController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CripsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\AlgoritmaController;


// HAPUS ATAU KOMENTARI SALAH SATU
// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::resource('kriteria', KriteriaController::class)->except(['create']);
Route::resource('alternatif', AlternatifController::class)->except(['create', 'show']);
Route::resource('crips', CripsController::class)->except(['index', 'create', 'show']);
Route::resource('/penilaian', PenilaianController::class);
Route::get('/perhitungan', [AlgoritmaController::class, 'index'])->name('perhitungan.index');
Route::get('/perhitungan/cetak-pdf', [AlgoritmaController::class, 'cetakPDF'])->name('perhitungan.cetakPDF');
Route::get('/perhitungan/export-csv', [AlgoritmaController::class, 'exportCSV'])->name('perhitungan.exportCSV');

?>