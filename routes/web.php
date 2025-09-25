<?php

use App\Filament\Pages\Laporan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RespondenController;
use App\Http\Controllers\ExportLaporanIkmPembinaanController;
use App\Http\Controllers\ExportLaporanIkmPembinaanPeriodeController;
use App\Http\Controllers\ExportLaporanIkmPelayananController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('/');

Route::get('/skmpembinaan/biodata', [RespondenController::class, 'createskmpembinaan'])->name('skmpembinaan.biodata');
Route::post('/skmpembinaan/biodata', [RespondenController::class, 'storeskmpembinaan'])->name('skmpembinaan.biodata');

Route::get('/skmpembinaan/{id}/skm', [RespondenController::class, 'skmpembinaan'])->name('skmpembinaan.skm');
Route::post('/skmpembinaan/{id}/skm', [RespondenController::class, 'submitskmpembinaan'])->name('skmpembinaan.submitskm');

Route::get('/skmpelayanan/biodata', [RespondenController::class, 'createskmpelayanan'])->name('skmpelayanan.biodata');
Route::post('/skmpelayanan/biodata', [RespondenController::class, 'storeskmpelayanan'])->name('skmpelayanan.biodata');

Route::get('/skmpelayanan/{id}/skm', [RespondenController::class, 'skmpelayanan'])->name('skmpelayanan.skm');
Route::post('/skmpelayanan/{id}/skm', [RespondenController::class, 'submitskmpelayanan'])->name('skmpelayanan.submitSkm');

Route::get('/kritik-saran/{id}', [RespondenController::class, 'kritiksaran'])->name('kritik-saran.form');
Route::post('/kritik-saran/{id}', [RespondenController::class, 'submitkritiksaran'])->name('kritik-saran.submit');

Route::get('/terima-kasih', function () {
    return view('thankyou');
})->name('terima-kasih');

Route::get('/export/laporan-ikm-pembinaan/{kegiatanNama}', [ExportLaporanIKMPembinaanController::class, 'export'])->name('export.ikm-pembinaan');

Route::get('/export/laporan-ikm-pembinaan-periode', [ExportLaporanIKMPembinaanPeriodeController::class, 'export'])->name('export.ikm-pembinaan-periode');

Route::get('/export-ikm-pelayanan', [ExportLaporanIKMPelayananController::class, 'export'])
    ->name('export.ikm.pelayanan');

Route::get('captcha/{config?}', '\Mews\Captcha\CaptchaController@getCaptcha');

// Route::get('/admin/laporan', Laporan::class)->name('filament.pages.laporan');
