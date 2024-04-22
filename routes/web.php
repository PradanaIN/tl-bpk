<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KamusController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RekomendasiController;
use App\Http\Controllers\TindakLanjutController;
use App\Http\Controllers\IdentifikasiDokumenController;
use App\Http\Controllers\PemutakhiranController;

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
// Login
Route::get('/auth/login', [LoginController::class, 'index'])->name('login');
Route::post('/auth/login', [LoginController::class, 'authenticate']);
Route::post('/auth/logout', [LoginController::class, 'logout'])->middleware('auth');


Route::middleware(['auth', 'prevent-back-button'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('permission:view dashboard');

    Route::middleware(['role:Admin||Super Admin'])->group(function () {
        // Kelola Pengguna
        Route::get('/kelola-pengguna', [UserController::class, 'index'])->middleware('permission:view user');
        Route::get('/kelola-pengguna/create', [UserController::class, 'create'])->middleware('permission:create user');
        Route::post('/kelola-pengguna', [UserController::class, 'store'])->middleware('permission:create user');
        Route::get('/kelola-pengguna/{user:id}', [UserController::class, 'show'])->middleware('permission:view user');
        Route::get('/kelola-pengguna/{user:id}/edit', [UserController::class, 'edit'])->middleware('permission:edit user');
        Route::put('/kelola-pengguna/{user:id}', [UserController::class, 'update'])->middleware('permission:edit user');
        Route::delete('/kelola-pengguna/{user:id}', [UserController::class, 'destroy'])->middleware('permission:delete user');
        // Kelola Kamus
        Route::get('/kelola-kamus', [KamusController::class, 'index'])->middleware('permission:view kamus');
        Route::get('/kelola-kamus/create', [KamusController::class, 'create'])->middleware('permission:create kamus');
        Route::post('/kelola-kamus', [KamusController::class, 'store'])->middleware('permission:create kamus');
        Route::get('/kelola-kamus/{kamus:id}', [KamusController::class, 'show'])->middleware('permission:view kamus');
        Route::get('/kelola-kamus/{kamus:id}/edit', [KamusController::class, 'edit'])->middleware('permission:edit kamus');
        Route::put('/kelola-kamus/{kamus:id}', [KamusController::class, 'update'])->middleware('permission:edit kamus');
        Route::delete('/kelola-kamus/{kamus:id}', [KamusController::class, 'destroy'])->middleware('permission:delete kamus');
    });

    Route::middleware(['role:Pimpinan|Tim Koordinator|Super Admin'])->group(function () {
        // Kelola Rekomendasi
        Route::get('/kelola-rekomendasi', [RekomendasiController::class, 'index'])->middleware('permission:view rekomendasi');
        Route::get('/kelola-rekomendasi/create', [RekomendasiController::class, 'create'])->middleware('permission:create rekomendasi');
        Route::post('/kelola-rekomendasi', [RekomendasiController::class, 'store'])->middleware('permission:create rekomendasi');
        Route::get('/kelola-rekomendasi/{rekomendasi:id}', [RekomendasiController::class, 'show'])->middleware('permission:view rekomendasi');
        Route::get('/kelola-rekomendasi/{rekomendasi:id}/edit', [RekomendasiController::class, 'edit'])->middleware('permission:edit rekomendasi');
        Route::put('/kelola-rekomendasi/{rekomendasi:id}', [RekomendasiController::class, 'update']);
        Route::delete('/kelola-rekomendasi/{rekomendasi:id}', [RekomendasiController::class, 'destroy'])->middleware('permission:delete rekomendasi');
        Route::get('/kelola-rekomendasi/{rekomendasi:id}/export', [RekomendasiController::class, 'export']);
    });

    Route::middleware(['role:Pimpinan|Tim Koordinator|Unit Kerja|Super Admin'])->group(function () {
        // Kelola Tindak Lanjut
        Route::get('/kelola-tindak-lanjut', [TindakLanjutController::class, 'index'])->middleware('permission:view tindak lanjut');
        Route::get('/kelola-tindak-lanjut/create', [TindakLanjutController::class, 'create'])->middleware('permission:create tindak lanjut');
        Route::post('/kelola-tindak-lanjut', [TindakLanjutController::class, 'store'])->middleware('permission:create tindak lanjut');
        Route::get('/kelola-tindak-lanjut/{tindak_lanjut:id}', [TindakLanjutController::class, 'show'])->middleware('permission:view tindak lanjut');
        Route::get('/kelola-tindak-lanjut/{tindak_lanjut:id}/edit', [TindakLanjutController::class, 'edit'])->middleware('permission:edit tindak lanjut');
        Route::put('/kelola-tindak-lanjut/{tindak_lanjut:id}', [TindakLanjutController::class, 'update'])->middleware('permission:edit tindak lanjut');
        Route::delete('/kelola-tindak-lanjut/{tindak_lanjut:id}', [TindakLanjutController::class, 'destroy'])->middleware('permission:delete tindak lanjut');
        Route::get('/kelola-tindak-lanjut/{tindak_lanjut:id}/generate', [TindakLanjutController::class, 'word']);
    });

    Route::middleware(['role:Tim Pemanantauan|Pengendali Teknis|Super Admin'])->group(function () {
        // Kelola Identifikasi Dokumen
        Route::get('/identifikasi', [IdentifikasiDokumenController::class, 'index'])->middleware('permission:view identifikasi');
        Route::get('/identifikasi/create', [IdentifikasiDokumenController::class, 'create'])->middleware('permission:create identifikasi');
        Route::post('/identifikasi', [IdentifikasiDokumenController::class, 'store'])->middleware('permission:create identifikasi');
        Route::get('/identifikasi/{tindak_lanjut:id}', [IdentifikasiDokumenController::class, 'show'])->middleware('permission:view identifikasi');
        Route::get('/identifikasi/{tindak_lanjut:id}/edit', [IdentifikasiDokumenController::class, 'edit'])->middleware('permission:edit identifikasi');
        Route::put('/identifikasi/{tindak_lanjut:id}', [IdentifikasiDokumenController::class, 'update'])->middleware('permission:edit identifikasi');
    });

    Route::middleware(['role:Pimpinan|Tim Koordinator|Super Admin'])->group(function () {
        // Pemutakhiran Status
        Route::get('pemutakhiran-status', [PemutakhiranController::class, 'index']);
        Route::get('pemutakhiran-status/{tindak_lanjut:id}', [PemutakhiranController::class, 'show']);
        Route::get('pemutakhiran-status/{tindak_lanjut:id}/edit', [PemutakhiranController::class, 'edit']);
        Route::put('pemutakhiran-status/{tindak_lanjut:id}', [PemutakhiranController::class, 'update']);
    });

    // Error 404
    Route::get('/error/404', function () {
        return view('errors.404');
    })->name('error.404');

    // Error 403
    Route::get('/error/403', function () {
        return view('errors.403');
    })->name('error.403');

    // Error 500
    Route::get('/error/500', function () {
        return view('errors.500');
    })->name('error.500');
});
