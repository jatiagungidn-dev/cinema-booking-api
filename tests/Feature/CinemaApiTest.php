<?php

namespace Tests\Feature;

use App\Models\Cinema;
use App\Models\Studio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CinemaApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_cinemas_can_be_listed(): void
    {
        Cinema::factory()->count(3)->create();

        $response = $this->getJson('/api/cinemas');

        $response->assertOk()->assertJsonCount(3, 'data');
    }

    public function single_cinema_can_be_restrived(): void
    {
        $cinema = Cinema::factory()->create();

        $response = $this->getJson("/api/cinemas/{$cinema->id}");

        $response->assertOk()->assertJsonPath('data.id', $cinema->id);
        $response->assertOk()->assertJsonPath('data.name', $cinema->name);
    }

    public function test_cinema_returns_404_when_not_found(): void
    {
        $response = $this->getJson('/api/cinemas/99999');

        $response->assertNotFound();
    }

    public function test_cinema_studio_can_be_listed(): void
    {
        $cinema = Cinema::factory()->create();

        Studio::factory()->count(2)->for($cinema)->create();

        $response = $this->getJson("/api/cinemas/{$cinema->id}/studios");

        $response->assertOk()->assertJsonCount(2, 'data');
    }

    public function test_studio_seat_can_be_listed(): void
    {
        $studio = Studio::factory()->create();

        $studio->seats()->createMany([
            ['row' => 'A', 'number' => 1],
            ['row' => 'A', 'number' => 2],
            ['row' => 'A', 'number' => 3],
        ]);

        $response = $this->getJson("/api/studios/{$studio->id}/seats");

        $response->assertOk()->assertJsonCount(3, 'data');
    }

    public function test_cinema_only_returns_its_own_studios(): void
    {
        $cinemaA = Cinema::factory()->create();
        $cinemaB = Cinema::factory()->create();

        Studio::factory()
            ->for($cinemaA)
            ->create([
                'name' => 'Studio A',
            ]);

        Studio::factory()
            ->for($cinemaB)
            ->create([
                'name' => 'Studio B',
            ]);

        $response = $this->getJson(
            "/api/cinemas/{$cinemaA->id}/studios"
        );

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Studio A');
    }
}
