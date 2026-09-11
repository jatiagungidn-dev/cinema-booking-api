<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CinemaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company().' Cinema',
            'address' => fake()->address(),
            'city' => fake()->city(),
        ];
    }
}
