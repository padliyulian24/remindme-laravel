<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginTest extends TestCase
{
    use DatabaseMigrations;
    
    public function test_valid_user(): void
    {
        User::factory()->create(['email' => 'alice@mail.com','password' => '123456']);
        $response = $this->postJson(
            '/api/session',
            ['email' => 'alice@mail.com','password' => '123456']
        );
        $response->assertStatus(200);
    }

    public function test_invalid_user(): void
    {
        $response = $this->postJson(
            '/api/session',
            ['email' => 'alice@mail.com','password' => '123']
        );
        $response->assertStatus(401);
    }

    public function test_invalid_form(): void
    {
        $response = $this->postJson('/api/session');
        $response->assertStatus(422);
    }
}
