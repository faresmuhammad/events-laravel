<?php

namespace Tests\Feature;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Database\Factories\UserFactory;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;


    //todo: test profile creation

    public function test_new_users_can_register(): void
    {
        $this->seed(RolesSeeder::class);
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => "1234567890",
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Registered Successfully'
            ]);

    }

    public function test_new_users_cannot_register_with_same_email_or_phone()
    {
        $this->seed(RolesSeeder::class);
        $user = User::factory()->create();
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => "1234567890",
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response->assertStatus(401)
            ->assertJson([
                "message" => "Validation Error",
                "errors" => [
                    "email" => [
                        "The email has already been taken."
                    ],
                    "phone" => [
                        "The phone has already been taken."
                    ]
                ]
            ]);
    }
}
