<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use RolesSeeder;
use App\Models\User;
use App\Models\Kamus;
use App\Models\Rekomendasi;
use Illuminate\Support\Str;
use App\Models\TindakLanjut;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
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
        // \App\Models\Rekomendasi::factory(50)->create();
        // \App\Models\TindakLanjut::factory(1)->create();
        $this->call(KamusSeeder::class);


        // Roles
        $roles = [
            'Admin',
            'Pimpinan',
            'Operator Unit Kerja',
            'Tim Koordinator',
            'Tim Pemantauan Wilayah I',
            'Tim Pemantauan Wilayah II',
            'Tim Pemantauan Wilayah III',
            'Pengendali Teknis',
            'Badan Pemeriksa Keuangan',
            'Super Admin'
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role],
                ['name' => $role]
            );
        }

        // Permission
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
            Permission::updateOrCreate(
                ['name' => $permission],
                ['name' => $permission]
            );
        }

        // Assign Permission to Role
        $permissions = [
            'Admin' => ['view dashboard', 'view kamus', 'create kamus', 'edit kamus', 'delete kamus', 'view user', 'create user', 'edit user', 'delete user'],
            'Pimpinan' => ['view dashboard', 'view rekomendasi', 'view tindak lanjut', 'show rekomendasi', 'show tindak lanjut'],
            'Operator Unit Kerja' => ['view dashboard', 'view rekomendasi', 'view tindak lanjut', 'show rekomendasi', 'show tindak lanjut', 'edit tindak lanjut'],
            'Tim Koordinator' => ['view dashboard', 'view rekomendasi', 'create rekomendasi', 'edit rekomendasi', 'delete rekomendasi', 'show rekomendasi', 'view tindak lanjut', 'create tindak lanjut', 'edit tindak lanjut', 'delete tindak lanjut', 'show tindak lanjut'],
            'Tim Pemantauan Wilayah I' => ['view dashboard', 'view rekomendasi', 'show rekomendasi', 'view tindak lanjut', 'show tindak lanjut', 'edit tindak lanjut', 'view identifikasi', 'create identifikasi', 'edit identifikasi'],
            'Tim Pemantauan Wilayah II' => ['view dashboard', 'view rekomendasi', 'show rekomendasi', 'view tindak lanjut', 'show tindak lanjut', 'edit tindak lanjut', 'view identifikasi', 'create identifikasi', 'edit identifikasi'],
            'Tim Pemantauan Wilayah III' => ['view dashboard', 'view rekomendasi', 'show rekomendasi', 'view tindak lanjut', 'show tindak lanjut', 'edit tindak lanjut', 'view identifikasi', 'create identifikasi', 'edit identifikasi'],
            'Pengendali Teknis' => ['view dashboard', 'view rekomendasi', 'show rekomendasi', 'view tindak lanjut', 'show tindak lanjut', 'edit tindak lanjut', 'view identifikasi', 'create identifikasi', 'edit identifikasi'],
            'Badan Pemeriksa Keuangan' => ['view rekomendasi', 'show rekomendasi'],
            'Super Admin' => ['view dashboard', 'view kamus', 'create kamus', 'edit kamus', 'delete kamus', 'view user', 'create user', 'edit user', 'delete user', 'view rekomendasi', 'create rekomendasi', 'edit rekomendasi', 'delete rekomendasi', 'show rekomendasi', 'view tindak lanjut', 'create tindak lanjut', 'edit tindak lanjut', 'delete tindak lanjut', 'show tindak lanjut', 'view identifikasi', 'create identifikasi', 'edit identifikasi'],
        ];

        foreach ($permissions as $roleName => $permissionList) {
            $role = Role::findByName($roleName);
            $role->givePermissionTo($permissionList);
        }


        // create super admin
        User::create([
            'id' => Str::uuid()->toString(),
            'nama' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'unit_kerja' => 'Inspektorat Utama',
            'role' => 'Super Admin',
            'unit_kerja_id' => 546
        ]);
        User::create([
            'id' => Str::uuid()->toString(),
            'nama' => 'BPS Provinsi Jawa Tengah',
            'email' => 'bpsjateng@example.org',
            'password' => Hash::make('password'),
            'unit_kerja' => 'BPS PROVINSI JAWA TENGAH',
            'role' => 'Operator Unit Kerja',
            'unit_kerja_id' => 194
        ]);

        User::create([
            'id' => Str::uuid()->toString(),
            'nama' => 'Biro Umum',
            'email' => 'biroumum@example.org',
            'password' => Hash::make('password'),
            'unit_kerja' => 'Biro Umum',
            'role' => 'Operator Unit Kerja',
            'unit_kerja_id' => 519
        ]);

        User::create([
            'id' => Str::uuid()->toString(),
            'nama' => 'Tim Koordinator',
            'email' => 'timkoordinator@example.com',
            'password' => Hash::make('password'),
            'unit_kerja' => 'Inspektorat Utama',
            'role' => 'Tim Koordinator',
            'unit_kerja_id' => 546
        ]);

        User::create([
            'id' => Str::uuid()->toString(),
            'nama' => 'Tim Pemantauan Wilayah II',
            'email' => 'timpemantauanwilayahdua@example.org',
            'password' => Hash::make('password'),
            'unit_kerja' => 'Inspektorat Wilayah II',
            'role' => 'Tim Pemantauan Wilayah II',
            'unit_kerja_id' => 538
        ]);

        User::create([
            'id' => Str::uuid()->toString(),
            'nama' => 'Tim Pemantauan Wilayah I',
            'email' => 'timpemantauanwilayahsatu@example.org',
            'password' => Hash::make('password'),
            'unit_kerja' => 'Inspektorat Wilayah I',
            'role' => 'Tim Pemantauan Wilayah I',
            'unit_kerja_id' => 537
        ]);


        // Assign Role to User
        $users = User::all();
        foreach ($users as $user) {
            switch ($user->role) {
                case 'Admin':
                    $user->assignRole('Admin');
                    break;
                case 'Pimpinan':
                    $user->assignRole('Pimpinan');
                    break;
                case 'Operator Unit Kerja':
                    $user->assignRole('Operator Unit Kerja');
                    break;
                case 'Tim Koordinator':
                    $user->assignRole('Tim Koordinator');
                    break;
                case 'Tim Pemantauan Wilayah I':
                case 'Tim Pemantauan Wilayah II':
                case 'Tim Pemantauan Wilayah III':
                case 'Pengendali Teknis':
                    $role = Role::where('name', $user->role)->first(); // Dapatkan objek peran berdasarkan nama peran
                    if ($role) {
                        $user->assignRole($role); // Gunakan objek peran untuk menetapkan peran ke pengguna
                    }
                    break;
                case 'Badan Pemeriksa Keuangan':
                    $user->assignRole('Badan Pemeriksa Keuangan');
                    break;
                case 'Super Admin':
                    $user->assignRole('Super Admin');
                    break;
            }
        }
    }
}
