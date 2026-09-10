<?php

namespace Database\Factories;

use App\Models\Studio;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeatFactory extends Factory
{
    public function definition(): array
    {
        return [
            'studio_id' => Studio::factory(),
            'row' => fake()->randomElement(['A', 'B', 'C', 'D', 'E']),
            'number' => fake()->numberBetween(1, 20),
        ];
    }
}
