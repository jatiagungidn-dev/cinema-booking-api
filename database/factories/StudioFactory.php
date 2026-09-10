<?php

namespace Database\Factories;

use App\Models\Cinema;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudioFactory extends Factory
{
    public function definition(): array
    {
        return [
            'cinema_id' => Cinema::factory(),
            'name' => 'Studio'.fake()->numberBetween(1, 10),
            'capacity' => fake()->numberBetween(50, 200),
        ];
    }
}
