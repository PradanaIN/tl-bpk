<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set timezone
        date_default_timezone_set('Asia/Jakarta');

        // Set default locale
        Carbon::setLocale('id_ID');

        $permissions = [
            // Dashboard
            'view dashboard',
            // Kamus
            'view kamus',
            'create kamus',
            'edit kamus',
            'delete kamus',
            // User
            'view user',
            'create user',
            'edit user',
            'delete user',
            // Rekomendasi
            'view rekomendasi',
            'create rekomendasi',
            'edit rekomendasi',
            'delete rekomendasi',
            'show rekomendasi',
            // Tindak Lanjut
            'view tindak lanjut',
            'create tindak lanjut',
            'edit tindak lanjut',
            'delete tindak lanjut',
            'show tindak lanjut',
            // Identifikasi
            'view identifikasi',
            'create identifikasi',
            'edit identifikasi',
        ];

        foreach ($permissions as $permission) {
            Gate::define($permission, function ($user) use ($permission) {
                return $user->hasPermissionTo($permission);
            });
        }

        // Roles
        $roles = [
            'Admin',
            'Pimpinan',
            'Unit Kerja',
            'Operator Unit Kerja',
            'Tim Koordinator',
            // 'Ketua Tim Pemantauan',
            // 'Ketua Tim Pemantauan II',
            // 'Ketua Tim Pemantauan III',
            // 'Anggota Tim Pemantauan',
            'Tim Pemantauan Wilayah I',
            'Tim Pemantauan Wilayah II',
            'Tim Pemantauan Wilayah III',
            // 'Anggota Tim Pemantauan II',
            // 'Anggota Tim Pemantauan III',
            'Pengendali Teknis',
            'Badan Pemeriksa Keuangan',
            'Super Admin'
        ];

        foreach ($roles as $role) {
            Gate::define($role, function ($user) use ($role) {
                return $user->hasRole($role);
            });
        }

    }
}
