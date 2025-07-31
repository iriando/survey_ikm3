<?php

use App\Filament\Pages\Laporan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RespondenController;

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

Route::get('/terima-kasih', function () {
    return view('thankyou');
})->name('terima-kasih');

// Route::get('/admin/laporan', Laporan::class)->name('filament.pages.laporan');
