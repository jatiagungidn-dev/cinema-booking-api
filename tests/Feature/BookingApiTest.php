<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Movie;
use App\Models\Seat;
use App\Models\Showtime;
use App\Models\Studio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_can_be_created(): void
    {
        $user = User::factory()->create(['id' => 1]);

        $movie = Movie::factory()->create();

        $studio = Studio::factory()->create([
            'capacity' => 50,
        ]);

        $seat = Seat::create([
            'studio_id' => $studio->id,
            'row' => 'A',
            'number' => 1,
        ]);

        $showtime = Showtime::factory()
            ->for($movie)
            ->for($studio)
            ->create([
                'price' => 50000,
            ]);

        $response = $this->postJson('/api/bookings', [
            'showtime_id' => $showtime->id,
            'seat_ids' => [$seat->id],
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.showtime_id', $showtime->id)
            ->assertJsonPath('data.status', 'PENDING')
            ->assertJsonPath('data.total_amount', 50000);
    }

    public function test_booking_requires_showtime_and_seats(): void
    {
        $response = $this->postJson('/api/bookings', []);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'showtime_id',
                'seat_ids',
            ]);
    }

    public function test_booking_rejects_duplicate_seat_ids(): void
    {
        $showtime = Showtime::factory()->create();

        $seat = Seat::create([
            'studio_id' => $showtime->studio_id,
            'row' => 'A',
            'number' => 1,
        ]);

        $response = $this->postJson('/api/bookings', [
            'showtime_id' => $showtime->id,
            'seat_ids' => [$seat->id, $seat->id],
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'seat_ids.1',
            ]);
    }

    public function test_bookings_can_be_listed(): void
    {
        $user = User::factory()->create([
            'id' => 1,
        ]);

        $booking = Booking::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->getJson('/api/bookings');

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_booking_requires_showtime_id(): void
    {
        $response = $this->postJson('/api/bookings', [
            'seat_ids' => [1],
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'showtime_id',
            ]);
    }

    public function test_booking_requires_seat_ids(): void
    {
        $response = $this->postJson('/api/bookings', [
            'showtime_id' => 1,
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'seat_ids',
            ]);
    }

    public function test_booking_rejects_seat_from_another_studio(): void
    {
        $movie = Movie::factory()->create();

        $showtimeStudio = Studio::factory()->create();
        $otherStudio = Studio::factory()->create();

        $showtime = Showtime::factory()
            ->for($movie)
            ->for($showtimeStudio)
            ->create();

        $seat = Seat::create([
            'studio_id' => $otherStudio->id,
            'row' => 'A',
            'number' => 1,
        ]);

        $response = $this->postJson('/api/bookings', [
            'showtime_id' => $showtime->id,
            'seat_ids' => [$seat->id],
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'seat_ids',
            ]);
    }
}
