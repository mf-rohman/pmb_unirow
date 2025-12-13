<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\FormulirController;
use App\Http\Controllers\ProfileController;
use App\Models\District;
use App\Models\Regency;
use App\Models\Village;
use Illuminate\Support\Facades\Route;

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
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// GROUP ROUTE YANG MEMBUTUHKAN LOGIN
Route::middleware(['auth', 'verified'])->group(function () {
    
    // 1. Route Formulir Pendaftaran
    Route::get('/formulir-pendaftaran', [FormulirController::class, 'create'])->name('formulir.create');
    Route::post('/formulir-pendaftaran', [FormulirController::class, 'store'])->name('formulir.store');

    Route::get('/formulir-pendaftaran/edit', [FormulirController::class, 'edit'])->name('formulir.edit');
    Route::put('/formulir-pendaftaran/update', [FormulirController::class, 'update'])->name('formulir.update');

    Route::post('/upload-dokumen', [FormulirController::class, 'uploadDokumen'])->name('dokumen.upload');

    Route::get('/cetak-kartu', [FormulirController::class, 'cetakKartu'])->name('cetak.kartu');

    Route::get('/api/check-token', [FormulirController::class, 'checkToken'])->name('api.check_token');

});


// 2. API SEDERHANA UNTUK WILAYAH (Diakses oleh JavaScript di Form)
// Kita taruh di web.php saja biar mudah (karena API stateless butuh setup token)
Route::prefix('api-wilayah')->group(function() {
    
    // Ambil Kabupaten berdasarkan ID Provinsi
    Route::get('/regencies/{province_id}', function ($province_id) {
        return Regency::where('province_id', $province_id)->get();
    });

    // Ambil Kecamatan berdasarkan ID Kabupaten
    Route::get('/districts/{regency_id}', function ($regency_id) {
        return District::where('regency_id', $regency_id)->get();
    });

    // Ambil Desa berdasarkan ID Kecamatan
    Route::get('/villages/{district_id}', function ($district_id) {
        return Village::where('district_id', $district_id)->get();
    });
});

Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);

require __DIR__.'/auth.php';
