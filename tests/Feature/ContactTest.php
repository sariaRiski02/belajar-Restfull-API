<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactTest extends TestCase
{
    public function test_createContactSuccess()
    {
        $this->seed([UserSeeder::class]);

        $data =  [
            "firstname" => "John",
            "lastname" => "Doe",
            "email" => "test123@test.com",
            "phone" => "1234567890"
        ];

        $authorization = [
            "Authorization" => "test"
        ];

        $response = $this->post('/api/contacts', $data, $authorization);
        // $response->assertStatus(201);
        $response->assertJson(
            [
                "data" => [
                    "id" => 1,
                    "firstname" => "John",
                    "lastname" => "Doe",
                    "email" => "test123@test.com",
                    "phone" => "1234567890"
                ]
            ]
        );

        $this->assertDatabaseHas('contacts', $data);
    }

    public function test_createContactFailed()
    {
        $this->seed([UserSeeder::class]);
        $response = $this->post('/api/contacts', [
            "firstname" => "John",
            "lastname" => "Doe",
            "email" => "test123@test.com",
            "phone" => "1234567890"
        ], [
            "Authorization" => "salah"
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            "errors"  => [
                "message" => ["Unauthorized"]
            ]
        ]);
    }

    public function test_createContactFailedValidation()
    {
        $this->seed([UserSeeder::class]);
        $response = $this->post('/api/contacts', [
            "firstname" => "John",
            "lastname" => "Doe",
            "email" => "test123",
            "phone" => "1234567890"
        ], [
            "Authorization" => "test"
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            "errors" => [
                "email" => ["The email field must be a valid email address."]
            ]
        ]);
    }
}
