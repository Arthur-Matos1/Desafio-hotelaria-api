<?php

namespace Tests\Feature;

use Tests\TestCase;

class RoomApiTest extends TestCase
{
    public function test_room_creation_requires_required_fields(): void
    {
        $response = $this->postJson('/api/rooms', []);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'hotel_id',
            'name'
        ]);
    }
}