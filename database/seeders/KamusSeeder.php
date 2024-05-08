<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KamusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kamus')->insert([
            [
                'id' => Str::uuid(),
                'nama' => 'Laporan Keuangan',
                'jenis' => 'Pemeriksaan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'nama' => 'Pemeriksaan Dengan Tujuan Tertentu',
                'jenis' => 'Pemeriksaan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'nama' => 'Kinerja',
                'jenis' => 'Pemeriksaan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'nama' => 'Belanja',
                'jenis' => 'Temuan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'nama' => 'SPI',
                'jenis' => 'Temuan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

}
