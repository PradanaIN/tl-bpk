<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KamusController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RekomendasiController;
use App\Http\Controllers\IdentifikasiController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PemutakhiranController;
use App\Http\Controllers\TindakLanjutController;
use App\Http\Controllers\MasterRekomendasiController;

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

    Route::middleware(['role:Super Admin||Super Admin'])->group(function () {
        // Master Pengguna
        Route::get('/master-pengguna', [UserController::class, 'index'])->middleware('permission:view user');
        Route::get('/master-pengguna/create', [UserController::class, 'create'])->middleware('permission:create user');
        Route::post('/master-pengguna', [UserController::class, 'store'])->middleware('permission:create user');
        Route::get('/master-pengguna/{user:id}', [UserController::class, 'show'])->middleware('permission:view user');
        Route::get('/master-pengguna/{user:id}/edit', [UserController::class, 'edit'])->middleware('permission:edit user');
        Route::put('/master-pengguna/{user:id}', [UserController::class, 'update'])->middleware('permission:edit user');
        Route::delete('/master-pengguna/{user:id}', [UserController::class, 'destroy'])->middleware('permission:delete user');
        // Master Kamus
        Route::get('/master-kamus', [KamusController::class, 'index'])->middleware('permission:view kamus');
        Route::get('/master-kamus/create', [KamusController::class, 'create'])->middleware('permission:create kamus');
        Route::post('/master-kamus', [KamusController::class, 'store'])->middleware('permission:create kamus');
        Route::get('/master-kamus/{kamus:id}', [KamusController::class, 'show'])->middleware('permission:view kamus');
        Route::get('/master-kamus/{kamus:id}/edit', [KamusController::class, 'edit'])->middleware('permission:edit kamus');
        Route::put('/master-kamus/{kamus:id}', [KamusController::class, 'update'])->middleware('permission:edit kamus');
        Route::delete('/master-kamus/{kamus:id}', [KamusController::class, 'destroy'])->middleware('permission:delete kamus');
    });

    Route::middleware(['role:Pimpinan|Tim Koordinator|Super Admin'])->group(function () {
        // Kelola Rekomendasi
        Route::get('/rekomendasi', [RekomendasiController::class, 'index'])->middleware('permission:view rekomendasi');
        Route::get('/rekomendasi/create', [RekomendasiController::class, 'create'])->middleware('permission:create rekomendasi');
        Route::post('/rekomendasi', [RekomendasiController::class, 'store'])->middleware('permission:create rekomendasi');
        Route::get('/rekomendasi/{rekomendasi:id}', [RekomendasiController::class, 'show'])->middleware('permission:view rekomendasi');
        Route::get('/rekomendasi/{rekomendasi:id}/edit', [RekomendasiController::class, 'edit'])->middleware('permission:edit rekomendasi');
        Route::put('/rekomendasi/{rekomendasi:id}', [RekomendasiController::class, 'update']);
        Route::get('/rekomendasi/{rekomendasi:id}/nextSemester', [RekomendasiController::class, 'nextSemester'])->middleware('permission:edit rekomendasi');
        Route::post('/rekomendasi/{rekomendasi:id}', [RekomendasiController::class, 'createNextSemester']);
        Route::delete('/rekomendasi/{rekomendasi:id}', [RekomendasiController::class, 'destroy'])->middleware('permission:delete rekomendasi');
        Route::get('/rekomendasi/{rekomendasi:id}/export', [RekomendasiController::class, 'export']);
    });

    Route::middleware(['role:Super Admin'])->group(function () {
        // Master Rekomendasi
        Route::get('/master-rekomendasi', [MasterRekomendasiController::class, 'index']);
        Route::get('/master-rekomendasi/create', [MasterRekomendasiController::class, 'create']);
        Route::post('/master-rekomendasi', [MasterRekomendasiController::class, 'store']);
        Route::get('/master-rekomendasi/{rekomendasi:id}', [MasterRekomendasiController::class, 'show']);
        Route::get('/master-rekomendasi/{rekomendasi:id}/edit', [MasterRekomendasiController::class, 'edit']);
        Route::put('/master-rekomendasi/{rekomendasi:id}', [MasterRekomendasiController::class, 'update']);
        Route::delete('/master-rekomendasi/{rekomendasi:id}', [MasterRekomendasiController::class, 'destroy']);
        Route::get('/master-rekomendasi/{rekomendasi:id}/export', [MasterRekomendasiController::class, 'export']);
    });

    Route::middleware(['role:Pimpinan|Tim Koordinator|Operator Unit Kerja|Super Admin'])->group(function () {
        // Kelola Tindak Lanjut
        Route::get('/tindak-lanjut', [TindakLanjutController::class, 'index'])->middleware('permission:view tindak lanjut');
        Route::get('/tindak-lanjut/create', [TindakLanjutController::class, 'create'])->middleware('permission:create tindak lanjut');
        Route::post('/tindak-lanjut', [TindakLanjutController::class, 'store'])->middleware('permission:create tindak lanjut');
        Route::get('/tindak-lanjut/{tindak_lanjut:id}', [TindakLanjutController::class, 'show'])->middleware('permission:view tindak lanjut');
        Route::get('/tindak-lanjut/{tindak_lanjut:id}/edit', [TindakLanjutController::class, 'edit'])->middleware('permission:edit tindak lanjut');
        Route::put('/tindak-lanjut/{tindak_lanjut:id}', [TindakLanjutController::class, 'update'])->middleware('permission:edit tindak lanjut');
        Route::delete('/tindak-lanjut/{tindak_lanjut:id}', [TindakLanjutController::class, 'destroy'])->middleware('permission:delete tindak lanjut');
        Route::get('/tindak-lanjut/{tindak_lanjut:id}/generate', [TindakLanjutController::class, 'word']);
        Route::put('/tindak-lanjut/{tindak_lanjut:id}/updateDeadline', [TindakLanjutController::class, 'updateDeadline']);
    });

    Route::middleware(['role:Tim Pemantauan Wilayah I|Tim Pemantauan Wilayah II|Tim Pemantauan Wilayah III|Pengendali Teknis|Super Admin'])->group(function () {
        // Kelola Identifikasi Dokumen
        Route::get('/identifikasi', [IdentifikasiController::class, 'index'])->middleware('permission:view identifikasi');
        Route::get('/identifikasi/create', [IdentifikasiController::class, 'create'])->middleware('permission:create identifikasi');
        Route::post('/identifikasi', [IdentifikasiController::class, 'store'])->middleware('permission:create identifikasi');
        Route::get('/identifikasi/{tindak_lanjut:id}', [IdentifikasiController::class, 'show'])->middleware('permission:view identifikasi');
        Route::get('/identifikasi/{tindak_lanjut:id}/edit', [IdentifikasiController::class, 'edit'])->middleware('permission:edit identifikasi');
        Route::put('/identifikasi/{tindak_lanjut:id}', [IdentifikasiController::class, 'update'])->middleware('permission:edit identifikasi');
    });

    Route::middleware(['role:Pimpinan|Tim Koordinator|Super Admin'])->group(function () {
        // Pemutakhiran Status
        Route::get('pemutakhiran-status', [PemutakhiranController::class, 'index']);
        Route::get('pemutakhiran-status/{rekomendasi:id}', [PemutakhiranController::class, 'show']);
        Route::get('pemutakhiran-status/{rekomendasi:id}/edit', [PemutakhiranController::class, 'edit']);
        Route::put('pemutakhiran-status/{rekomendasi:id}', [PemutakhiranController::class, 'update']);
        Route::put('pemutakhiran-status/{rekomendasi:id}/siptl', [PemutakhiranController::class, 'uploadBuktiInputSIPTL']);
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
