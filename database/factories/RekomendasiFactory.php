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
            'pemeriksaan' => $this->faker->word,
            'jenis_pemeriksaan' => $this->faker->word,
            // year from 2010 to 2024
            'tahun_pemeriksaan' => $this->faker->numberBetween(2010, 2024),
            'hasil_pemeriksaan' => $this->faker->sentence,
            'jenis_temuan' => $this->faker->word,
            'uraian_temuan' => $this->faker->sentence,
            'rekomendasi' => $this->faker->sentence,
            'catatan_rekomendasi' => $this->faker->sentence,
            'status_rekomendasi' => $this->faker->randomElement(['Sesuai', 'Belum Sesuai', 'Belum Ditindaklanjuti', 'Tidak Dapat Ditindaklanjuti']),
        ];
    }
}
