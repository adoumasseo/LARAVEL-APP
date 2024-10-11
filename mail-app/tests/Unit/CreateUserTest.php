<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_user()
    {
        // Create a user using the factory
        $user = User::factory()->create();

        // Assert that the user was created successfully in the database
        $this->assertDatabaseHas('users', [
            'email' => $user->email,
        ]);

        // Optional: Check specific fields
        $this->assertEquals('password', bcrypt('password'));
    }
}