<?php

namespace Database\Factories;

use App\Models\UnitKerja;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;
use SebastianBergmann\CodeCoverage\Report\Xml\Unit;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $unit_kerja = UnitKerja::all()->pluck('nama')->toArray();
        $role = ["Admin", "Pimpinan", "Operator", "Tim Koordinator", "Tim Pemantauan", "Pengendali Teknis", "Badan Pemeriksa Keuangan"];

        return [
            'nama' => fake()->name(),
            'unit_kerja' => $unit_kerja[array_rand($unit_kerja)],
            'role' => $role[array_rand($role)],
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
