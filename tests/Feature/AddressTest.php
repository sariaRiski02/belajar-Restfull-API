<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Contact;
use Database\Seeders\ContactSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddressTest extends TestCase
{


    public function test_CreateAddressSuccess()
    {

        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $user = User::where('username', 'test')->first();

        $response = $this->post("/api/contacts/$user->id/addresses", [
            'street' => 'imam bonjol',
            'city' => 'manado',
            'province' => 'sulawesi utara',
            'country' => 'indonesia',
            'postal_code' => '953785'
        ], [
            "Authorization" => $user->token
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'street' => 'imam bonjol',
                'city' => 'manado',
                'province' => 'sulawesi utara',
                'country' => 'indonesia',
                'postal_code' => '953785'
            ]
        ]);
    }

    public function test_CreateAddressFailed_country_empty()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);

        $user = User::where('username', 'test')->first();
        $response = $this->post(
            "/api/contacts/$user->id/addresses",
            [
                'street' => 'imam bonjol',
                'city' => 'manado',
                'province' => 'sulawesi utara',
                // 'country' => 'indonesia',
                'postal_code' => '953785'
            ],
            ["Authorization" => $user->token]
        );

        $response->assertStatus(400);
        $response->assertJson([

            "errors" => [
                'messages' => [
                    'country' =>
                    ['The country field is required.']
                ]
            ]

        ]);
    }

    public function test_createAddresswithUserNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $user = User::where('username', 'test')->first();
        $id = $user->id + 404;
        $response = $this->post(
            "/api/contacts/$id/addresses",
            [
                'street' => 'imam bonjol',
                'city' => 'manado',
                'province' => 'sulawesi utara',
                'country' => 'indonesia',
                'postal_code' => '953785'
            ],
            [
                "Authorization" => $user->token
            ]
        );

        $response->assertStatus(400);
        $response->assertJson([
            "message" => "Contact not found"
        ]);
    }

    public function test_CreateAddressUnauthorized()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $user = User::where('username', 'test')->first();
        $id = $user->id + 404;
        $response = $this->post(
            "/api/contacts/$id/addresses",
            [
                'street' => 'imam bonjol',
                'city' => 'manado',
                'province' => 'sulawesi utara',
                'country' => 'indonesia',
                'postal_code' => '953785'
            ],
            [
                "Authorization" => 'token salah'
            ]
        );

        $response->assertStatus(401);
        $response->assertJson([
            "errors" => [
                "message" => [
                    "Unauthorized"
                ]
            ]
        ]);
    }
}
