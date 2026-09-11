<?php

namespace Tests\Feature;

use Tests\TestCase;

class HealthApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_health_endpoint_returns_success(): void
    {
        $response = $this->getJson('/api/health');

        $response->assertOk()->assertJson([
            'status' => 'success',
            'message' => 'Cinema Booking API is running',
        ]);
    }
}
