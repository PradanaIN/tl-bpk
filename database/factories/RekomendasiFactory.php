<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rekomendasi>
 */
class RekomendasiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pemeriksaan' => $this->faker->sentence(),
            'jenis_pemeriksaan' => $this->faker->randomElement(['Laporan Keuangan', 'Kinerja']),
            'tahun_pemeriksaan' => $this->faker->numberBetween(2000, date('Y')), // Tahun acak antara 2000 dan tahun saat ini
            'hasil_pemeriksaan' => $this->faker->paragraph(),
            'jenis_temuan' => $this->faker->randomElement(['Belanja', 'SPI']),
            'uraian_temuan' => $this->faker->paragraph(),
            'rekomendasi' => $this->faker->paragraph(),
            'catatan_rekomendasi' => $this->faker->paragraph(),
            'status_rekomendasi' => $this->faker->randomElement(['Belum Sesuai', 'Sudah Sesuai', 'Proses', 'Tidak Ditindaklanjuti']),
        ];
    }
}
