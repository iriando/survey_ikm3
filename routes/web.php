<?php

use App\Filament\Pages\Laporan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RespondenController;
use App\Http\Controllers\ExportLaporanIKMPembinaanController;
use App\Http\Controllers\ExportLaporanIKMPembinaanPeriodeController;
use App\Http\Controllers\ExportLaporanIKMPelayananController;

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
Route::get('/skmpembinaan/{id}/kritik-saran', [RespondenController::class, 'kritiksaranrespembinaan'])->name('kritik-saranpembinaan.form');
Route::post('/skmpembinaan/{id}/kritik-saran', [RespondenController::class, 'submitkritiksaranrespembinaan'])->name('kritik-saranpembinaan.submit');

Route::get('/skmpelayanan/biodata', [RespondenController::class, 'createskmpelayanan'])->name('skmpelayanan.biodata');
Route::post('/skmpelayanan/biodata', [RespondenController::class, 'storeskmpelayanan'])->name('skmpelayanan.biodata');
Route::get('/skmpelayanan/{id}/skm', [RespondenController::class, 'skmpelayanan'])->name('skmpelayanan.skm');
Route::post('/skmpelayanan/{id}/skm', [RespondenController::class, 'submitskmpelayanan'])->name('skmpelayanan.submitSkm');
Route::get('/skmpelayanan/{id}/kritik-saran', [RespondenController::class, 'kritiksaranrespelayanan'])->name('kritik-saranpelayanan.form');
Route::post('/skmpelayanan/{id}/kritik-saran', [RespondenController::class, 'submitkritiksaranrespelayanan'])->name('kritik-saranpelayanan.submit');

Route::get('/skmtu', [RespondenController::class, 'indextu'])->name('skmtu.welcome');
Route::get('/skmtu/biodata', [RespondenController::class, 'createskmtu'])->name('skmtu.biodata');
Route::post('/skmtu/biodata', [RespondenController::class, 'storeskmtu'])->name('skmtu.biodata');
Route::get('/skmtu/{id}/skm', [RespondenController::class, 'skmtu'])->name('skmtu.skm');
Route::post('/skmtu/{id}/skm', [RespondenController::class, 'submitskmtu'])->name('skmtu.submitSkm');

Route::get('/terima-kasih', function () {
    return view('thankyou');
})->name('terima-kasih');

Route::get('/export/ikm-pembinaan/{id}', [ExportLaporanIKMPembinaanController::class, 'export'])
    ->name('export.ikm-pembinaan');

Route::get('/export/laporan-ikm-pembinaan-periode', [ExportLaporanIkmPembinaanPeriodeController::class, 'export'])->name('export.ikm-pembinaan-periode');

Route::get('/export-ikm-pelayanan', [ExportLaporanIkmPelayananController::class, 'export'])
    ->name('export.ikm.pelayanan');

Route::get('captcha/{config?}', '\Mews\Captcha\CaptchaController@getCaptcha');

// Route::get('/admin/laporan', Laporan::class)->name('filament.pages.laporan');
