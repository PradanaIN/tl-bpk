<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kamus>
 */
class KamusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $nama = ['Laporan Keuangan', 'Pemeriksaan Dengan Tujuan Tertentu', 'Kinerja', 'Belanja', 'SPI'];
        $jenis = ['Pemeriksaan', 'Temuan'];

        return [
            'nama' => $nama[array_rand($nama)],
            'jenis' => $jenis[array_rand($jenis)],
            'created_at' => now(),
        ];
    }

}
