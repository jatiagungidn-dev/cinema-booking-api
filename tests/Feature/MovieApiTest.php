<?php

namespace Tests\Feature;

use App\Models\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MovieApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_movies_can_be_listed(): void
    {
        Movie::factory()->count(3)->create();

        $response = $this->getJson('/api/movies');

        $response->assertOk()->assertJsonCount(3, 'data');
    }

    public function test_single_movie_can_be_retrieved(): void
    {
        $movie = Movie::factory()->create();

        $response = $this->getJson("/api/movies/{$movie->id}");

        $response->assertOk()->assertJsonPath('data.id', $movie->id)->assertJsonPath('data.title', $movie->title);
    }

    public function test_movie_returns_404_when_not_found(): void
    {
        $response = $this->getJson('/api/movies/99999');

        $response->assertNotFound();
    }
}
