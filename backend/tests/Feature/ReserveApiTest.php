<?php

namespace Tests\Feature;

use Tests\TestCase;

class ReserveApiTest extends TestCase
{
    public function test_reserve_creation_requires_required_fields(): void
    {
        $response = $this->postJson('/api/reserves', []);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'hotel_id',
            'room_id',
            'check_in',
            'check_out',
            'total',
            'guests',
            'dailies'
        ]);
    }
}