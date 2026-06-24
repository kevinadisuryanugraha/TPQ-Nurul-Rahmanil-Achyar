<?php

namespace Database\Factories;

use App\Models\Pendaftar;
use Illuminate\Database\Eloquent\Factories\Factory;

class PendaftarFactory extends Factory
{
    protected $model = Pendaftar::class;

    public function definition(): array
    {
        return [
            'nama_lengkap' => fake()->name(),
            'tempat_lahir' => fake()->city(),
            'tanggal_lahir' => fake()->date('Y-m-d', '2018-12-31'),
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'nama_orang_tua' => fake()->name('male'),
            'no_wa' => '08' . fake()->numerify('##########'),
            'alamat' => fake()->address(),
            'pernah_mengaji' => fake()->boolean(),
            'level_mengaji_sebelumnya' => null,
            'catatan_tambahan' => null,
            'status' => 'baru',
            'catatan_internal' => null,
            'user_id' => null,
        ];
    }

    public function sudahMengaji(): static
    {
        return $this->state(fn () => [
            'pernah_mengaji' => true,
            'level_mengaji_sebelumnya' => fake()->randomElement(['Iqra 1', 'Iqra 2', 'Iqra 3', 'Iqra 4', 'Al-Qur\'an']),
        ]);
    }

    public function diterima(): static
    {
        return $this->state(fn () => [
            'status' => 'diterima',
            'user_id' => \App\Models\User::factory(),
        ]);
    }
}
