<?php

namespace Database\Factories;

use App\Models\Showtime;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'showtime_id' => Showtime::factory(),
            'status' => 'CONFIRMED',
            'total_amount' => fake()->numberBetween(40000, 300000),
        ];
    }
}
