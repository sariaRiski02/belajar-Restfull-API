<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_Register_Success(): void
    {
        $this->post('/api/users', [
            "name" => "riski",
            "username" => "riski122",
            "password" => "riski12345678"
        ])->assertStatus(201)
            ->assertJson([
                "data" => [
                    "name" => "riski",
                    "username" => "riski122"
                ]
            ]);
    }

    public function test_Register_Failed(): void
    {
        $this->post('/api/users', [
            "name" => "riski",
            "username" => '',
            "password" => "riski123"
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "username" => [
                        "The username field is required."
                    ]
                ]
            ]);
    }

    public function test_Register_Already_Exists(): void
    {
        $this->post('/api/users', [
            "name" => "riski",
            "username" => "riski122",
            "password" => "riski123"
        ]);

        $this->post('/api/users', [
            "name" => "riski",
            "username" => "riski122",
            "password" => "riski12345678"
        ])->assertStatus(400)->assertJson([
            "errors" => [
                "username" => [
                    "The username has already been taken."
                ]
            ]
        ]);
    }
}
