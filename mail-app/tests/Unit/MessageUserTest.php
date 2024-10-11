<?php

namespace Tests\Feature;

use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_message()
    {
        // Create a message using the factory
        $message = Message::factory()->create();

        // Assert that the message was created successfully in the database
        $this->assertDatabaseHas('messages', [
            'from' => $message->from,
        ]);

        // Optional: Check if 'to' and 'files' fields were stored correctly
        $this->assertIsArray(json_decode($message->to));
        $this->assertIsArray(json_decode($message->files));
    }
}
