<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KamusController;
use App\Http\Controllers\RekomendasiController;
use App\Http\Controllers\TindakLanjutController;

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

Route::get('/dashboard', function () {
    return view('livewire.dashboard', [
        'title' => 'Dashboard'

    ]);
});

Route::get('/dashboard2', function () {
    return view('livewire.dashboard2');
});


// Kelola Pengguna
Route::resource('/kelola-pengguna', UserController::class)->except([
    'show', 'edit', 'update', 'destroy']);
    // 'show', 'edit', 'update', 'destroy' manual karena route resource tidak bisa menangani route dengan parameter
Route::get('/kelola-pengguna/{user:id}', [UserController::class, 'show']);
Route::get('/kelola-pengguna/{user:id}/edit', [UserController::class, 'edit']);
Route::put('/kelola-pengguna/{user:id}', [UserController::class, 'update']);
Route::delete('/kelola-pengguna/{user:id}', [UserController::class, 'destroy']);

// Kelola Kamus
Route::resource('/kelola-kamus', KamusController::class)->except([
    'show', 'edit', 'update', 'destroy']);
    // 'show', 'edit', 'update', 'destroy' manual karena route resource tidak bisa menangani route dengan parameter
Route::get('/kelola-kamus/{kamus:id}', [KamusController::class, 'show']);
Route::get('/kelola-kamus/{kamus:id}/edit', [KamusController::class, 'edit']);
Route::put('/kelola-kamus/{kamus:id}', [KamusController::class, 'update']);
Route::delete('/kelola-kamus/{kamus:id}', [KamusController::class, 'destroy']);

// Kelola Rekomendasi
Route::resource('/kelola-rekomendasi', RekomendasiController::class)->except([
    'show', 'edit', 'update', 'destroy']);
    // 'show', 'edit', 'update', 'destroy' manual karena route resource tidak bisa menangani route dengan parameter
Route::get('/kelola-rekomendasi/{rekomendasi:id}', [RekomendasiController::class, 'show']);
Route::get('/kelola-rekomendasi/{rekomendasi:id}/edit', [RekomendasiController::class, 'edit']);
Route::put('/kelola-rekomendasi/{rekomendasi:id}', [RekomendasiController::class, 'update']);
Route::delete('/kelola-rekomendasi/{rekomendasi:id}', [RekomendasiController::class, 'destroy']);

// Kelola Tindak Lanjut
Route::resource('/kelola-tindak-lanjut', TindakLanjutController::class)->except([
    'show', 'edit', 'update', 'destroy']);
    // 'show', 'edit', 'update', 'destroy' manual karena route resource tidak bisa menangani route dengan parameter
Route::get('/kelola-tindak-lanjut/{tindak_lanjut:id}', [TindakLanjutController::class, 'show']);
Route::get('/kelola-tindak-lanjut/{tindak_lanjut:id}/edit', [TindakLanjutController::class, 'edit']);
Route::put('/kelola-tindak-lanjut/{tindak_lanjut:id}', [TindakLanjutController::class, 'update']);
Route::delete('/kelola-tindak-lanjut/{tindak_lanjut:id}', [TindakLanjutController::class, 'destroy']);

// Unit Kerja Import Data
Route::get('/import', [App\Http\Controllers\UnitKerjaController::class, 'import']);
