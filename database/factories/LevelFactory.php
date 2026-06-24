<?php

namespace Database\Factories;

use App\Models\Level;
use Illuminate\Database\Eloquent\Factories\Factory;

class LevelFactory extends Factory
{
    protected $model = Level::class;

    public function definition(): array
    {
        static $urutan = 0;
        $urutan++;
        return [
            'nama' => match ($urutan) {
                1 => 'Pra-Iqra',
                2 => 'Iqra 1',
                3 => 'Iqra 2',
                4 => 'Iqra 3',
                5 => 'Iqra 4',
                6 => 'Iqra 5',
                7 => 'Iqra 6',
                8 => 'Al-Qur\'an',
                default => 'Level ' . $urutan,
            },
            'urutan' => $urutan,
        ];
    }
}
