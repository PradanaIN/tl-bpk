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
use App\Http\Controllers\OldRekomendasiController;
use App\Http\Controllers\OldTindakLanjutController;

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

// storage link
Route::get('/storage-link', function(){
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    echo 'success';
});

// default route
Route::get('/', function () {
    return redirect('/auth/login');
});
// Login
Route::get('/auth/login', [LoginController::class, 'index'])->name('login');
Route::post('/auth/login', [LoginController::class, 'authenticate']);
Route::post('/auth/logout', [LoginController::class, 'logout'])->middleware('auth');


Route::middleware(['auth', 'prevent-back-button'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('permission:view dashboard');

    Route::middleware(['role:Super Admin|Admin'])->group(function () {
        // Kelola Pengguna
        Route::get('/kelola-pengguna', [UserController::class, 'index'])->middleware('permission:view user');
        Route::get('/kelola-pengguna/create', [UserController::class, 'create'])->middleware('permission:create user');
        Route::post('/kelola-pengguna', [UserController::class, 'store'])->middleware('permission:create user');
        Route::get('/kelola-pengguna/{user:id}', [UserController::class, 'show'])->middleware('permission:view user');
        Route::get('/kelola-pengguna/{user:id}/edit', [UserController::class, 'edit'])->middleware('permission:edit user');
        Route::put('/kelola-pengguna/{user:id}', [UserController::class, 'update'])->middleware('permission:edit user');
        Route::delete('/kelola-pengguna/{user:id}', [UserController::class, 'destroy'])->middleware('permission:delete user');
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
        Route::get('/rekomendasi/{rekomendasi:id}', [RekomendasiController::class, 'show'])->middleware('permission:show rekomendasi');
        Route::get('/rekomendasi/{rekomendasi:id}/edit', [RekomendasiController::class, 'edit'])->middleware('permission:edit rekomendasi');
        Route::put('/rekomendasi/{rekomendasi:id}', [RekomendasiController::class, 'update'])->middleware('permission:edit rekomendasi');
        Route::delete('/rekomendasi/{rekomendasi:id}', [RekomendasiController::class, 'destroy'])->middleware('permission:delete rekomendasi');
        Route::get('/rekomendasi/{rekomendasi:id}/export', [RekomendasiController::class, 'export'])->middleware('permission:export rekomendasi');
        Route::get('/rekomendasi/{rekomendasi:id}/nextSemester', [RekomendasiController::class, 'nextSemester'])->middleware('permission:edit rekomendasi');
        Route::post('/rekomendasi/{rekomendasi:id}', [RekomendasiController::class, 'createNextSemester'])->middleware('permission:edit rekomendasi');
    });

    Route::middleware(['role:Super Admin|Tim Koordinator|Admin|Pimpinan'])->group(function () {
        // Old Rekomendasi
        Route::get('/old-rekomendasi', [OldRekomendasiController::class, 'index'])->middleware('permission:view old rekomendasi');
        Route::get('/old-rekomendasi/create', [OldRekomendasiController::class, 'create'])->middleware('permission:create old rekomendasi');
        Route::post('/old-rekomendasi', [OldRekomendasiController::class, 'store'])->middleware('permission:create old rekomendasi');
        Route::get('/old-rekomendasi/{rekomendasi:id}', [OldRekomendasiController::class, 'show'])->middleware('permission:show old rekomendasi');
        Route::get('/old-rekomendasi/{rekomendasi:id}/edit', [OldRekomendasiController::class, 'edit'])->middleware('permission:edit old rekomendasi');
        Route::put('/old-rekomendasi/{rekomendasi:id}', [OldRekomendasiController::class, 'update'])->middleware('permission:edit old rekomendasi');
        Route::delete('/old-rekomendasi/{rekomendasi:id}', [OldRekomendasiController::class, 'destroy'])->middleware('permission:delete old rekomendasi');
        Route::get('/old-rekomendasi/{rekomendasi:id}/export', [OldRekomendasiController::class, 'export'])->middleware('permission:export old rekomendasi');
    });

    Route::middleware(['role:Super Admin|Tim Koordinator|Admin'])->group(function () {
        // Old Tindak Lanjut
        Route::delete('/old-tindak-lanjut/{tindak_lanjut:id}', [OldTindakLanjutController::class, 'destroy'])->middleware('permission:delete old tindak rekomendasi');
        Route::delete('/old-tindak-lanjut/{tindak_lanjut?}', [OldTindakLanjutController::class, 'destroy'])->name('oldtindaklanjut.destroy')->middleware('permission:delete old rekomendasi');
    });

    Route::middleware(['role:Pimpinan|Tim Koordinator|Operator Unit Kerja|Pimpinan Unit Kerja|Super Admin'])->group(function () {
        // Kelola Tindak Lanjut
        Route::get('/tindak-lanjut', [TindakLanjutController::class, 'index'])->middleware('permission:view tindak lanjut');
        Route::get('/tindak-lanjut/create', [TindakLanjutController::class, 'create'])->middleware('permission:create tindak lanjut');
        Route::post('/tindak-lanjut', [TindakLanjutController::class, 'store'])->middleware('permission:create tindak lanjut');
        Route::get('/tindak-lanjut/{tindak_lanjut:id}', [TindakLanjutController::class, 'show'])->middleware('permission:show tindak lanjut');
        Route::get('/tindak-lanjut/{tindak_lanjut:id}/edit', [TindakLanjutController::class, 'edit'])->middleware('permission:edit tindak lanjut');
        Route::put('/tindak-lanjut/{tindak_lanjut:id}', [TindakLanjutController::class, 'update'])->middleware('permission:edit tindak lanjut');
        Route::delete('/tindak-lanjut/{tindak_lanjut:id}', [TindakLanjutController::class, 'destroy'])->middleware('permission:delete tindak lanjut');
        Route::delete('/tindak-lanjut/{tindak_lanjut?}', [TindakLanjutController::class, 'destroy'])->name('tindaklanjut.destroy')->middleware('permission:delete tindak lanjut');
        Route::get('/tindak-lanjut/{tindak_lanjut:id}/generate', [TindakLanjutController::class, 'word'])->middleware('permission:export tindak lanjut');
        Route::put('/tindak-lanjut/{tindak_lanjut:id}/updateDeadline', [TindakLanjutController::class, 'updateDeadline'])->middleware('permission:edit tindak lanjut');
    });

    Route::middleware(['role:Tim Pemantauan Wilayah I|Tim Pemantauan Wilayah II|Tim Pemantauan Wilayah III|Super Admin'])->group(function () {
        // Kelola Identifikasi Dokumen
        Route::get('/identifikasi', [IdentifikasiController::class, 'index'])->middleware('permission:view identifikasi');
        Route::get('/identifikasi/create', [IdentifikasiController::class, 'create'])->middleware('permission:create identifikasi');
        Route::post('/identifikasi', [IdentifikasiController::class, 'store'])->middleware('permission:create identifikasi');
        Route::get('/identifikasi/{tindak_lanjut:id}', [IdentifikasiController::class, 'show'])->middleware('permission:show identifikasi');
        Route::get('/identifikasi/{tindak_lanjut:id}/edit', [IdentifikasiController::class, 'edit'])->middleware('permission:edit identifikasi');
        Route::put('/identifikasi/{tindak_lanjut:id}', [IdentifikasiController::class, 'update'])->middleware('permission:edit identifikasi');
    });

    Route::middleware(['role:Pimpinan|Tim Koordinator|Super Admin'])->group(function () {
        // Pemutakhiran Status
        Route::get('pemutakhiran-status', [PemutakhiranController::class, 'index'])->middleware('permission:view pemutakhiran');
        Route::get('pemutakhiran-status/{rekomendasi:id}', [PemutakhiranController::class, 'show'])->middleware('permission:show pemutakhiran');
        Route::get('pemutakhiran-status/{rekomendasi:id}/edit', [PemutakhiranController::class, 'edit'])->middleware('permission:edit pemutakhiran');
        Route::put('pemutakhiran-status/{rekomendasi:id}', [PemutakhiranController::class, 'update'])->middleware('permission:edit pemutakhiran');
        Route::put('pemutakhiran-status/{rekomendasi:id}/siptl', [PemutakhiranController::class, 'uploadBuktiInputSIPTL'])->middleware('permission:edit pemutakhiran');
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
