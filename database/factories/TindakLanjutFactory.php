<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TindakLanjut>
 */
class TindakLanjutFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tindak_lanjut' => $this->faker->sentence(),
            'unit_kerja' => $this->faker->randomElement(['Unit Kerja A', 'Unit Kerja B', 'Unit Kerja C']),
            'tim_pemantauan' => $this->faker->randomElement(['Tim Pemantauan A', 'Tim Pemantauan B', 'Tim Pemantauan C']),
            'tenggat_waktu' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'), // Tenggat waktu dalam setahun ke depan dari sekarang
            'bukti_tindak_lanjut' => $this->faker->randomElement(['Dokumen 1', 'Belum Diunggah!']),
            'detail_bukti_tindak_lanjut' =>$this->faker->sentence(),
            'upload_by' => 'Super Admin',
            'upload_at' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'), // Tanggal upload dalam setahun ke belakang dari sekarang
            'status_tindak_lanjut' => $this->faker->randomElement(['Belum Sesuai', 'Sesuai', 'Belum Ditindaklanjuti', 'Tidak Ditindaklanjuti']),
            'catatan_tindak_lanjut' => $this->faker->sentence(),
            'rekomendasi_id' => \App\Models\Rekomendasi::factory(),
        ];
    }
}
