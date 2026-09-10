<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Seat;
use App\Models\Showtime;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingSeatFactory extends Factory
{
    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory(),
            'showtime_id' => Showtime::factory(),
            'seat_id' => Seat::factory(),
            'price' => fake()->numberBetween(40000, 100000),
        ];
    }
}
