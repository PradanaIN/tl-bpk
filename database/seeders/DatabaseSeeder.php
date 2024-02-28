<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // \App\Models\User::factory(1)->create();
        // \App\Models\Kamus::factory(15)->create();
        // \App\Models\Rekomendasi::factory(1)->create();
        // \App\Models\TindakLanjut::factory(1)->create();


        // // Roles
        // $roles = [
        //     'Admin',
        //     'Pimpinan',
        //     'Operator',
        //     'Tim Koordinator',
        //     'Ketua Tim Pemanantauan',
        //     // 'Ketua Tim Pemanantauan II',
        //     // 'Ketua Tim Pemanantauan III',
        //     'Anggota Tim Pemanantauan',
        //     // 'Anggota Tim Pemanantauan II',
        //     // 'Anggota Tim Pemanantauan III',
        //     'Pengendali Teknis',
        //     'Badan Pemeriksa Keuangan'
        // ];

        // foreach ($roles as $role) {
        //     Role::updateOrCreate(
        //         ['name' => $role],
        //         ['name' => $role]
        //     );
        // }

        // // Permission
        // $permissions = [
        //     // Dashboard
        //     'view dashboard',
        //     // Kamus
        //     'view kamus',
        //     'create kamus',
        //     'edit kamus',
        //     'delete kamus',
        //     // User
        //     'view user',
        //     'create user',
        //     'edit user',
        //     'delete user',
        //     // Rekomendasi
        //     'view rekomendasi',
        //     'create rekomendasi',
        //     'edit rekomendasi',
        //     'delete rekomendasi',
        //     'show rekomendasi',
        //     // Tindak Lanjut
        //     'view tindak lanjut',
        //     'create tindak lanjut',
        //     'edit tindak lanjut',
        //     'delete tindak lanjut',
        //     'show tindak lanjut',
        //     // Identifikasi
        //     'view identifikasi',
        //     'create identifikasi',
        //     'edit identifikasi',
        // ];

        // foreach ($permissions as $permission) {
        //     Permission::updateOrCreate(
        //         ['name' => $permission],
        //         ['name' => $permission]
        //     );
        // }

        // // Assign Permission to Role
        // $permissions = [
        //     'Admin' => ['view dashboard', 'view kamus', 'create kamus', 'edit kamus', 'delete kamus', 'view user', 'create user', 'edit user', 'delete user'],
        //     'Pimpinan' => ['view dashboard', 'view rekomendasi', 'view tindak lanjut', 'show rekomendasi', 'show tindak lanjut'],
        //     'Operator' => ['view dashboard', 'view rekomendasi', 'view tindak lanjut', 'show rekomendasi', 'show tindak lanjut', 'edit tindak lanjut'],
        //     'Tim Koordinator' => ['view dashboard', 'view rekomendasi', 'create rekomendasi', 'edit rekomendasi', 'delete rekomendasi', 'show rekomendasi', 'view tindak lanjut', 'create tindak lanjut', 'edit tindak lanjut', 'delete tindak lanjut', 'show tindak lanjut'],
        //     'Ketua Tim Pemanantauan' => ['view dashboard', 'view rekomendasi', 'show rekomendasi', 'view tindak lanjut', 'show tindak lanjut', 'edit tindak lanjut', 'view identifikasi', 'create identifikasi', 'edit identifikasi'],
        //     'Anggota Tim Pemanantauan' => ['view dashboard', 'view rekomendasi', 'show rekomendasi', 'view tindak lanjut', 'show tindak lanjut', 'edit tindak lanjut', 'view identifikasi', 'create identifikasi', 'edit identifikasi'],
        //     'Pengendali Teknis' => ['view dashboard', 'view rekomendasi', 'show rekomendasi', 'view tindak lanjut', 'show tindak lanjut', 'edit tindak lanjut', 'view identifikasi', 'create identifikasi', 'edit identifikasi'],
        //     'Badan Pemeriksa Keuangan' => ['view rekomendasi', 'show rekomendasi']
        // ];

        // foreach ($permissions as $roleName => $permissionList) {
        //     $role = Role::findByName($roleName);
        //     $role->givePermissionTo($permissionList);
        // }

        // // Assign Role to User
        // $users = \App\Models\User::all();
        // // apabila user memiliki role Admin maka assign admin
        // foreach ($users as $user) {
        //     if ($user->role === 'Admin') {
        //         $user->assignRole('Admin');
        //     } if ($user->role === 'Pimpinan') {
        //         $user->assignRole('Pimpinan');
        //     } if ($user->role === 'Operator') {
        //         $user->assignRole('Operator');
        //     } if ($user->role === 'Tim Koordinator') {
        //         $user->assignRole('Tim Koordinator');
        //     } if ($user->role === 'Ketua Tim Pemanantauan') {
        //         $user->assignRole('Ketua Tim Pemanantauan');
        //     } if ($user->role === 'Anggota Tim Pemanantauan') {
        //         $user->assignRole('Anggota Tim Pemanantauan');
        //     } if ($user->role === 'Pengendali Teknis') {
        //         $user->assignRole('Pengendali Teknis');
        //     } if ($user->role === 'Badan Pemeriksa Keuangan') {
        //         $user->assignRole('Badan Pemeriksa Keuangan');
        //     }
        // }
    }
}
