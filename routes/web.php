<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KamusController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RekomendasiController;
use App\Http\Controllers\TindakLanjutController;
use App\Http\Controllers\IdentifikasiDokumenController;

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
Route::get('/auth/login', [LoginController::class, 'index']);
Route::post('/auth/login', [LoginController::class, 'authenticate']);
Route::post('/auth/logout', [LoginController::class, 'logout']);

// dashboard
Route::get('/dashboard', [DashboardController::class, 'index']);

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

// Identifikasi Dokumen
Route::resource('/identifikasi-dokumen', IdentifikasiDokumenController::class)->except([
    'show', 'edit', 'update', 'destroy']);
    // 'show', 'edit', 'update', 'destroy' manual karena route resource tidak bisa menangani route dengan parameter
Route::get('/identifikasi-dokumen/{tindak_lanjut:id}', [IdentifikasiDokumenController::class, 'show']);
Route::get('/identifikasi-dokumen/{tindak_lanjut:id}/edit', [IdentifikasiDokumenController::class, 'edit']);
Route::put('/identifikasi-dokumen/{tindak_lanjut:id}', [IdentifikasiDokumenController::class, 'update']);
