<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Seat;
use App\Models\Showtime;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_can_be_created(): void
    {
        $user = User::factory()->create();

        $showtime = Showtime::factory()->create();

        $seat = Seat::factory()->create([
            'studio_id' => $showtime->studio_id,
        ]);

        $response = $this
            ->actingAs($user)
            ->postJson('/api/bookings', [
                'showtime_id' => $showtime->id,
                'seat_ids' => [$seat->id],
            ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.showtime_id', $showtime->id)
            ->assertJsonPath('data.status', 'PENDING')
            ->assertJsonPath(
                'data.total_amount',
                $showtime->price
            );

        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'showtime_id' => $showtime->id,
            'status' => 'PENDING',
            'total_amount' => $showtime->price,
        ]);

        $this->assertDatabaseHas('booking_seats', [
            'booking_id' => $response->json('data.id'),
            'showtime_id' => $showtime->id,
            'seat_id' => $seat->id,
            'price' => $showtime->price,
        ]);
    }

    public function test_booking_requires_showtime_and_seats(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->postJson('/api/bookings', []);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'showtime_id',
                'seat_ids',
            ]);
    }

    public function test_booking_rejects_duplicate_seat_ids(): void
    {
        $user = User::factory()->create();

        $showtime = Showtime::factory()->create();

        $seat = Seat::factory()->create([
            'studio_id' => $showtime->studio_id,
        ]);

        $response = $this
            ->actingAs($user)
            ->postJson('/api/bookings', [
                'showtime_id' => $showtime->id,
                'seat_ids' => [
                    $seat->id,
                    $seat->id,
                ],
            ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'seat_ids.1',
            ]);
    }

    public function test_bookings_can_be_listed(): void
    {
        $user = User::factory()->create();

        Booking::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->getJson('/api/bookings');

        $response
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_booking_requires_showtime_id(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->postJson('/api/bookings', [
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
        $user = User::factory()->create();

        $showtime = Showtime::factory()->create();

        $response = $this
            ->actingAs($user)
            ->postJson('/api/bookings', [
                'showtime_id' => $showtime->id,
            ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'seat_ids',
            ]);
    }

    public function test_unauthenticated_user_cannot_access_bookings(): void
    {
        $response = $this->getJson('/api/bookings');

        $response->assertUnauthorized();
    }

    public function test_unauthenticated_user_cannot_create_booking(): void
    {
        $response = $this->postJson('/api/bookings', [
            'showtime_id' => 1,
            'seat_ids' => [1],
        ]);

        $response->assertUnauthorized();
    }

    public function test_user_can_view_own_booking(): void
    {
        $user = User::factory()->create();

        $booking = Booking::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->getJson("/api/bookings/{$booking->id}");

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $booking->id);
    }

    public function test_user_cannot_view_another_users_booking(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $booking = Booking::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->getJson("/api/bookings/{$booking->id}");

        $response->assertForbidden();
    }

    public function test_user_only_sees_own_bookings(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $ownBooking = Booking::factory()->create([
            'user_id' => $user->id,
        ]);

        Booking::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->getJson('/api/bookings');

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath(
                'data.0.id',
                $ownBooking->id
            );
    }

    public function test_booking_is_created_for_authenticated_user(): void
    {
        $user = User::factory()->create();

        $showtime = Showtime::factory()->create();

        $seat = Seat::factory()->create([
            'studio_id' => $showtime->studio_id,
        ]);

        $response = $this
            ->actingAs($user)
            ->postJson('/api/bookings', [
                'showtime_id' => $showtime->id,
                'seat_ids' => [$seat->id],
            ]);

        $response->assertCreated();

        $this->assertDatabaseHas('bookings', [
            'id' => $response->json('data.id'),
            'user_id' => $user->id,
        ]);
    }
}
