<?php

namespace Database\Factories;

use App\Models\Movie;
use App\Models\Studio;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShowtimeFactory extends Factory
{
    public function definition(): array
    {
        $startsAt = fake()->dateTimeBetween('now', '+30 days');

        return [
            'movie_id' => Movie::factory(),
            'studio_id' => Studio::factory(),
            'starts_at' => $startsAt,
            'ends_at' => (clone $startsAt)->modify('+2 hours'),
            'price' => fake()->numberBetween(40000, 100000),
        ];
    }
}
