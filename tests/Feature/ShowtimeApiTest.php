<?php

namespace Tests\Feature;

use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Showtime;
use App\Models\Studio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowtimeApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_showtimes_can_be_listed(): void
    {
        $movie = Movie::factory()->create();
        $studio = Studio::factory()->create();

        Showtime::factory()->count(3)->create([
            'movie_id' => $movie->id,
            'studio_id' => $studio->id,
        ]);

        $response = $this->getJson('/api/showtimes');

        $response
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_single_showtime_can_be_retrieved(): void
    {
        $movie = Movie::factory()->create();
        $studio = Studio::factory()->create();
        $showtime = Showtime::factory()->create([
            'movie_id' => $movie->id,
            'studio_id' => $studio->id,
        ]);

        $response = $this->getJson(
            "/api/showtimes/{$showtime->id}"
        );

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $showtime->id);
    }

    public function test_showtime_returns_404_when_not_found(): void
    {
        $response = $this->getJson('/api/showtimes/99999');

        $response->assertNotFound();
    }

    public function test_showtimes_can_be_filtered_by_movie(): void
    {
        $movie = Movie::factory()->create();
        $otherMovie = Movie::factory()->create();

        Showtime::factory()
            ->count(2)
            ->create([
                'movie_id' => $movie->id,
            ]);

        Showtime::factory()->create([
            'movie_id' => $otherMovie->id,
        ]);

        $response = $this->getJson(
            "/api/showtimes?movie_id={$movie->id}"
        );

        $response
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_showtimes_can_be_filtered_by_cinema(): void
    {
        $cinema = Cinema::factory()->create();
        $otherCinema = Cinema::factory()->create();
        $studio = Studio::factory()->for($cinema)->create();
        $otherStudio = Studio::factory()->for($otherCinema)->create();

        Showtime::factory()
            ->count(2)
            ->create([
                'studio_id' => $studio->id,
            ]);

        Showtime::factory()->create([
            'studio_id' => $otherStudio->id,
        ]);

        $response = $this->getJson(
            "/api/showtimes?cinema_id={$cinema->id}"
        );

        $response
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_showtimes_can_be_filtered_by_date(): void
    {
        $movie = Movie::factory()->create();
        $studio = Studio::factory()->create();

        Showtime::factory()->create([
            'movie_id' => $movie->id,
            'studio_id' => $studio->id,
            'starts_at' => '2026-09-15 13:00:00',
        ]);

        Showtime::factory()->create([
            'movie_id' => $movie->id,
            'studio_id' => $studio->id,
            'starts_at' => '2026-09-16 13:00:00',
        ]);

        $response = $this->getJson(
            '/api/showtimes?date=2026-09-15'
        );

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }
}
