<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use function Laravel\Prompts\password;

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



    public function test_login_succcess()
    {

        $this->seed([UserSeeder::class]);

        $this->post('/api/users/login', [
            "username" => "test",
            "password" => "test12345678"
        ])->assertStatus(200)
            ->assertJsonStructure([
                "data" => [
                    "id",
                    "name",
                    "username",
                    "token"
                ]
            ]);
    }

    public function testLoginFailed_oneOFformNull()
    {
        //username null
        $this->seed([UserSeeder::class]);
        $this->post('/api/users/login', [
            "username" => "",
            "password" => "riski12345678"
        ])
            ->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "username" => [
                        "The username field is required."
                    ]
                ]
            ]);
        // password null

        $this->post('/api/users/login', [
            "username" => "test",
            "password" => ""
        ])
            ->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "password" => [
                        "The password field is required."
                    ]
                ]
            ]);
    }


    public function test_login_failed()
    {
        $this->seed([UserSeeder::class]);
        // username salah
        $this->post('/api/users/login', [
            "username" => "username_salah",
            "password" => "test12345678"
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "username or password is incorrect."
                    ]
                ]
            ]);
        // password salah
        $this->post('/api/users/login', [
            "username" => "test",
            "password" => "password_salah"
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "username or password is incorrect."
                    ]
                ]
            ]);
    }


    // test for get

    public function test_GetSuccess()
    {
        $this->seed([UserSeeder::class]);

        $this->get('/api/users/current', [
            "Authorization" => 'test'
        ])->assertStatus(200)->assertJson([
            "data" => [
                "id" => 1,
                "name" => "test",
                "username" => "test",
                "token" => "test"
            ]
        ]);
    }

    public function test_Unauthorize()
    {
        $this->seed([UserSeeder::class]);
        $this->get('/api/users/current')
            ->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "Unauthorized"
                    ]
                ]
            ]);
    }
    public function test_getFailed()
    {
        $this->get('/api/users/current')
            ->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "Unauthorized"
                    ]
                ]
            ]);
    }
    public function test_UpdateUserSuccess()
    {
        $this->seed([UserSeeder::class]);
        $this->patch('/api/users/current', [
            "name" => "ganti",
            "password" => "ganti"
        ], ["Authorization" => "test"])
            ->assertJson([
                "data" => [
                    "id" => 1,
                    "name" => "ganti",
                    "username" => "test",
                    "token" => "test"
                ]
            ]);
    }

    public function test_UpdateUserFailed()
    {
        $this->seed([UserSeeder::class]);
        $this->patch('/api/users/current', [
            "name" => "ganti",
            "password" => "ganti"
        ])
            ->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "Unauthorized"
                    ]
                ]
            ]);
    }


    public function test_logoutSuccess()
    {

        $this->seed([UserSeeder::class]);

        $this->delete(uri: '/api/users/logout', headers: [
            "Authorization" => "test"
        ])->assertJson([
            "data" => true
        ]);

        $this->assertDatabaseMissing(table: 'users', data: [
            "token" => "test"
        ]);
    }

    public function test_logutFailed()
    {
        $this->delete(uri: '/api/users/logout', headers: [
            "Authorization" => "salah"
        ])
            ->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "Unauthorized"
                    ]
                ]
            ]);
    }
}
