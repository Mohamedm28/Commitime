<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_child_registration_requires_parent_email()
    {
        $response = $this->postJson('/api/register', [
            'first_name' => 'mohamed',
            'last_name' => 'saleh',
            'email' => 'mohamed2@gmail.com',
            'password' => '2812003',
            'password_confirmation' => '2812003',
            'is_under_18' => true,
            // parent_email missing here to test validation
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['parent_email']);
    }

    public function test_successful_child_registration()
    {
        $response = $this->postJson('/api/register', [
            'first_name' => 'mohamed',
            'last_name' => 'saleh',
            'email' => 'mohamed2@gmail.com',
            'password' => '2812003',
            'password_confirmation' => '2812003',
            'is_under_18' => true,
            'parent_email' => 'saleh@gmail.com'
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'mohamed2@gmail.com', 'is_under_18' => true]);
    }

    public function test_successful_login()
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token', 'user']);
    }
}

