<?php

namespace Tests\Feature\Reminder;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CrudTest extends TestCase
{
    use DatabaseMigrations;

    public function test_list_with_valid_token(): void
    {
        User::factory()->create(['email' => 'alice@mail.com','password' => '123456']);
        $login = $this->postJson('/api/session',['email' => 'alice@mail.com','password' => '123456']);
        $token = $login['data']['access_token'];

        $response = $this->getJson('/api/reminders?page=1&limit=5',['Authorization' => $token]);
        $response->assertStatus(200)->assertJson(['ok' => true]);
    }

    public function test_list_with_invalid_token(): void
    {
        $token = "123";
        $response = $this->getJson('/api/reminders?page=1&limit=5',['Authorization' => $token]);
        $response->assertStatus(401)->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_create_with_valid_data(): void
    {
        User::factory()->create(['email' => 'alice@mail.com','password' => '123456']);
        $login = $this->postJson('/api/session',['email' => 'alice@mail.com','password' => '123456']);
        $token = $login['data']['access_token'];

        $response = $this->postJson(
            '/api/reminders',
            [
                'title' => 'reminder1',
                'description' => 'description1',
                'remind_at' => '2023/12/15 21:00',
                'event_at' => '2023/12/16 21:00'
            ],
            ['Authorization' => $token]
        );
        $response->assertStatus(200)->assertJsonPath('data.title', 'reminder1');
    }

    public function test_create_with_invalid_data(): void
    {
        User::factory()->create(['email' => 'alice@mail.com','password' => '123456']);
        $login = $this->postJson('/api/session',['email' => 'alice@mail.com','password' => '123456']);
        $token = $login['data']['access_token'];

        $response = $this->postJson(
            '/api/reminders',
            [
                'description' => 'description1',
                'remind_at' => '2023/12/15 21:00',
                'event_at' => '2023/12/16 21:00'
            ],
            ['Authorization' => $token]
        );
        $response->assertStatus(422)->assertJson(['message' => 'The title field is required.']);
    }

    public function test_show_with_valid_data(): void
    {
        User::factory()->create(['email' => 'alice@mail.com','password' => '123456']);
        $login = $this->postJson('/api/session',['email' => 'alice@mail.com','password' => '123456']);
        $token = $login['data']['access_token'];

        $this->postJson(
            '/api/reminders',
            [
                'title' => 'reminder1',
                'description' => 'description1',
                'remind_at' => '2023/12/15 21:00',
                'event_at' => '2023/12/16 21:00'
            ],
            ['Authorization' => $token]
        );

        $response = $this->getJson('/api/reminders/1',['Authorization' => $token]);
        $response->assertStatus(200)->assertJsonPath('data.description', 'description1');
    }

    public function test_show_with_invalid_id(): void
    {
        User::factory()->create(['email' => 'alice@mail.com','password' => '123456']);
        $login = $this->postJson('/api/session',['email' => 'alice@mail.com','password' => '123456']);
        $token = $login['data']['access_token'];

        $this->postJson(
            '/api/reminders',
            [
                'title' => 'reminder1',
                'description' => 'description1',
                'remind_at' => '2023/12/15 21:00',
                'event_at' => '2023/12/16 21:00'
            ],
            ['Authorization' => $token]
        );

        $response = $this->getJson('/api/reminders/2',['Authorization' => $token]);
        $response->assertStatus(404);
    }

    public function test_update_with_valid_data(): void
    {
        User::factory()->create(['email' => 'alice@mail.com','password' => '123456']);
        $login = $this->postJson('/api/session',['email' => 'alice@mail.com','password' => '123456']);
        $token = $login['data']['access_token'];

        $this->postJson(
            '/api/reminders',
            [
                'title' => 'reminder1',
                'description' => 'description1',
                'remind_at' => '2023/12/15 21:00',
                'event_at' => '2023/12/16 21:00'
            ],
            ['Authorization' => $token]
        );

        $response = $this->putJson('/api/reminders/1',['title' => 'reminder edit'],['Authorization' => $token]);
        $response->assertStatus(200)->assertJsonPath('data.title', 'reminder edit');
    }

    public function test_update_with_invalid_id(): void
    {
        User::factory()->create(['email' => 'alice@mail.com','password' => '123456']);
        $login = $this->postJson('/api/session',['email' => 'alice@mail.com','password' => '123456']);
        $token = $login['data']['access_token'];

        $this->postJson(
            '/api/reminders',
            [
                'title' => 'reminder1',
                'description' => 'description1',
                'remind_at' => '2023/12/15 21:00',
                'event_at' => '2023/12/16 21:00'
            ],
            ['Authorization' => $token]
        );

        $response = $this->putJson('/api/reminders/2',['title' => 'reminder edit'],['Authorization' => $token]);
        $response->assertStatus(404);
    }
    
    public function test_delete_with_valid_data(): void
    {
        User::factory()->create(['email' => 'alice@mail.com','password' => '123456']);
        $login = $this->postJson('/api/session',['email' => 'alice@mail.com','password' => '123456']);
        $token = $login['data']['access_token'];

        $this->postJson(
            '/api/reminders',
            [
                'title' => 'reminder1',
                'description' => 'description1',
                'remind_at' => '2023/12/15 21:00',
                'event_at' => '2023/12/16 21:00'
            ],
            ['Authorization' => $token]
        );

        $response = $this->deleteJson('/api/reminders/1',[],['Authorization' => $token]);
        $response->assertStatus(200)->assertJson(['ok' => true]);
    }

    public function test_delete_with_invalid_id(): void
    {
        User::factory()->create(['email' => 'alice@mail.com','password' => '123456']);
        $login = $this->postJson('/api/session',['email' => 'alice@mail.com','password' => '123456']);
        $token = $login['data']['access_token'];

        $this->postJson(
            '/api/reminders',
            [
                'title' => 'reminder1',
                'description' => 'description1',
                'remind_at' => '2023/12/15 21:00',
                'event_at' => '2023/12/16 21:00'
            ],
            ['Authorization' => $token]
        );

        $response = $this->deleteJson('/api/reminders/2',[],['Authorization' => $token]);
        $response->assertStatus(404)->assertJson(['ok' => false]);
    }
}
