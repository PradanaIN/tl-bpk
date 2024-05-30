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
            'Pimpinan Unit Kerja',
            'Operator Unit Kerja',
            'Tim Koordinator',
            'Tim Pemantauan Wilayah I',
            'Tim Pemantauan Wilayah II',
            'Tim Pemantauan Wilayah III',
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
            'export rekomendasi',
            // Tindak Lanjut
            'view tindak lanjut',
            'create tindak lanjut',
            'edit tindak lanjut',
            'show tindak lanjut',
            'delete tindak lanjut',
            'export tindak lanjut',
            // Identifikasi
            'view identifikasi',
            'create identifikasi',
            'edit identifikasi',
            'show identifikasi',
            // pemutakhiran
            'view pemutakhiran',
            'create pemutakhiran',
            'edit pemutakhiran',
            'show pemutakhiran',
            // old rekomendasi
            'view old rekomendasi',
            'create old rekomendasi',
            'edit old rekomendasi',
            'delete old rekomendasi',
            'show old rekomendasi',
            'export old rekomendasi',
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission],
                ['name' => $permission]
            );
        }

        // Assign Permission to Role
        $permissions = [
            // Admin hanya kamus dan user
            'Admin' => [
                'view kamus',
                'create kamus',
                'edit kamus',
                'delete kamus',
                'view user',
                'create user',
                'edit user',
                'delete user',
            ],
            // Tim Koordinator dashboard, rekomendasi, tindak lanjut, pemutakhiran, old rekomendasi
            'Tim Koordinator' => [
                'view dashboard',
                'view rekomendasi',
                'create rekomendasi',
                'edit rekomendasi',
                'delete rekomendasi',
                'show rekomendasi',
                'view tindak lanjut',
                'create tindak lanjut',
                'edit tindak lanjut',
                'show tindak lanjut',
                'delete tindak lanjut',
                'view pemutakhiran',
                'create pemutakhiran',
                'edit pemutakhiran',
                'show pemutakhiran',
                'view old rekomendasi',
                'create old rekomendasi',
                'edit old rekomendasi',
                'delete old rekomendasi',
                'show old rekomendasi',
            ],
            // Pimpinan sama dengan Tim Koordinator namun tidak bisa create, edit, dan delete
            'Pimpinan' => [
                'view dashboard',
                'view rekomendasi',
                'show rekomendasi',
                'view tindak lanjut',
                'show tindak lanjut',
                'view pemutakhiran',
                'show pemutakhiran',
                'view old rekomendasi',
                'show old rekomendasi',
            ],
            // Badan Pemeriksa Keuangan mirip dengan Pimpinan
            'Badan Pemeriksa Keuangan' => [
                'view dashboard',
                'view rekomendasi',
                'show rekomendasi',
                'view tindak lanjut',
                'show tindak lanjut',
                'view pemutakhiran',
                'show pemutakhiran',
                'view old rekomendasi',
                'show old rekomendasi',
            ],
            // Operator Unit Kerja hanya dashboard dan tindak lanjut
            'Operator Unit Kerja' => [
                'view dashboard',
                'view tindak lanjut',
                'create tindak lanjut',
                'edit tindak lanjut',
                'show tindak lanjut',
            ],
            // Pimpinan Unit Kerja sama dengan Operator Unit Kerja namun tidak bisa create, edit
            'Pimpinan Unit Kerja' => [
                'view dashboard',
                'view tindak lanjut',
                'show tindak lanjut',
            ],
            // Tim Pemantauan Wilayah I, II, III, hanya dashboard dan identifikasi
            'Tim Pemantauan Wilayah I' => [
                'view dashboard',
                'view identifikasi',
                'create identifikasi',
                'edit identifikasi',
                'show identifikasi',
            ],
            'Tim Pemantauan Wilayah II' => [
                'view dashboard',
                'view identifikasi',
                'create identifikasi',
                'edit identifikasi',
                'show identifikasi',
            ],
            'Tim Pemantauan Wilayah III' => [
                'view dashboard',
                'view identifikasi',
                'create identifikasi',
                'edit identifikasi',
                'show identifikasi',
            ],
            // Super Admin bisa semua
            'Super Admin' => [
                'view dashboard',
                'view kamus',
                'create kamus',
                'edit kamus',
                'delete kamus',
                'view user',
                'create user',
                'edit user',
                'delete user',
                'view rekomendasi',
                'create rekomendasi',
                'edit rekomendasi',
                'delete rekomendasi',
                'show rekomendasi',
                'export rekomendasi',
                'view tindak lanjut',
                'create tindak lanjut',
                'edit tindak lanjut',
                'show tindak lanjut',
                'delete tindak lanjut',
                'export tindak lanjut',
                'view identifikasi',
                'create identifikasi',
                'edit identifikasi',
                'show identifikasi',
                'view pemutakhiran',
                'create pemutakhiran',
                'edit pemutakhiran',
                'show pemutakhiran',
                'view old rekomendasi',
                'create old rekomendasi',
                'edit old rekomendasi',
                'delete old rekomendasi',
                'show old rekomendasi',
                'export old rekomendasi',
            ],
        ];

        foreach ($permissions as $roleName => $permissionList) {
            $role = Role::findByName($roleName);
            $role->givePermissionTo($permissionList);
        }


        // create User
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
            'nama' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'unit_kerja' => 'Inspektorat Utama',
            'role' => 'Admin',
            'unit_kerja_id' => 546
        ]);
        User::create([
            'id' => Str::uuid()->toString(),
            'nama' => 'BPS Provinsi Jawa Tengah',
            'email' => 'bpsjateng@example.com',
            'password' => Hash::make('password'),
            'unit_kerja' => 'BPS PROVINSI JAWA TENGAH',
            'role' => 'Operator Unit Kerja',
            'unit_kerja_id' => 194
        ]);
        User::create([
            'id' => Str::uuid()->toString(),
            'nama' => 'Pimpinan BPS Provinsi Jawa Tengah',
            'email' => 'pimpinanbpsjateng@example.com',
            'password' => Hash::make('password'),
            'unit_kerja' => 'BPS PROVINSI JAWA TENGAH',
            'role' => 'Pimpinan Unit Kerja',
            'unit_kerja_id' => 194
        ]);
        User::create([
            'id' => Str::uuid()->toString(),
            'nama' => 'Biro Umum',
            'email' => 'biroumum@example.com',
            'password' => Hash::make('password'),
            'unit_kerja' => 'Biro Umum',
            'role' => 'Operator Unit Kerja',
            'unit_kerja_id' => 519
        ]);
        User::create([
            'id' => Str::uuid()->toString(),
            'nama' => 'Pimpinan Biro Umum',
            'email' => 'pimpinanbiroumum@example.com',
            'password' => Hash::make('password'),
            'unit_kerja' => 'Biro Umum',
            'role' => 'Pimpinan Unit Kerja',
            'unit_kerja_id' => 519
        ]);
        User::create([
            'id' => Str::uuid()->toString(),
            'nama' => 'Polstat STIS',
            'email' => 'polstatstis@example.com',
            'password' => Hash::make('password'),
            'unit_kerja' => 'Politeknik Statistika STIS',
            'role' => 'Operator Unit Kerja',
            'unit_kerja_id' => 521
        ]);
        User::create([
            'id' => Str::uuid()->toString(),
            'nama' => 'Pimpinan Polstat STIS',
            'email' => 'pimpinanpolstatstis@example.com',
            'password' => Hash::make('password'),
            'unit_kerja' => 'Politeknik Statistika STIS',
            'role' => 'Pimpinan Unit Kerja',
            'unit_kerja_id' => 521
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
            'nama' => 'Tim Pemantauan Wilayah I',
            'email' => 'timpemantauanwilayahsatu@example.com',
            'password' => Hash::make('password'),
            'unit_kerja' => 'Inspektorat Wilayah I',
            'role' => 'Tim Pemantauan Wilayah I',
            'unit_kerja_id' => 537
        ]);
        User::create([
            'id' => Str::uuid()->toString(),
            'nama' => 'Tim Pemantauan Wilayah II',
            'email' => 'timpemantauanwilayahdua@example.com',
            'password' => Hash::make('password'),
            'unit_kerja' => 'Inspektorat Wilayah II',
            'role' => 'Tim Pemantauan Wilayah II',
            'unit_kerja_id' => 538
        ]);
        User::create([
            'id' => Str::uuid()->toString(),
            'nama' => 'Tim Pemantauan Wilayah III',
            'email' => 'timpemantauanwilayahtiga@example.com',
            'password' => Hash::make('password'),
            'unit_kerja' => 'Inspektorat Wilayah III',
            'role' => 'Tim Pemantauan Wilayah III',
            'unit_kerja_id' => 539
        ]);
        User::create([
            'id' => Str::uuid()->toString(),
            'nama' => 'Pimpinan Inspektorat Utama',
            'email' => 'pimpinan@example.com',
            'password' => Hash::make('password'),
            'unit_kerja' => 'Inspektorat Utama',
            'role' => 'Pimpinan',
            'unit_kerja_id' => 546
        ]);
        User::create([
            'id' => Str::uuid()->toString(),
            'nama' => 'Badan Pemeriksa Keuangan',
            'email' => 'bpk@example.com',
            'password' => Hash::make('password'),
            'unit_kerja' => 'Badan Pemeriksa Keuangan',
            'role' => 'Badan Pemeriksa Keuangan',
            'unit_kerja_id' => 547
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
                case 'Pimpinan Unit Kerja':
                    $user->assignRole('Pimpinan Unit Kerja');
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
