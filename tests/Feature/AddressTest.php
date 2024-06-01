<?php

namespace Tests\Feature;

use App\Models\Address;
use Tests\TestCase;
use App\Models\User;
use App\Models\Contact;
use Database\Seeders\UserSeeder;
use Database\Seeders\AddressSeeder;
use Database\Seeders\ContactSeeder;
use Database\Seeders\listAddressSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function PHPUnit\Framework\assertTrue;

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


    public function test_getAddressByIdSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();
        $response = $this->get("/api/contacts/$address->contact_id/addresses/$address->id", [
            'Authorization' => 'test'
        ]);

        $response->assertJson([
            'data' => [
                'id' => 1,
                'street' => 'JL test',
                'city' => 'test city',
                'province' => 'north test',
                'country' => 'test country',
                'postal_code' => '953785'
            ]
        ]);
    }

    public function test_getAddressByIdFailedContactId_notFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();
        $response = $this->get('/api/contacts/' . $address->contact_id + 1 . '/addresses/' . $address->id, [
            'Authorization' => 'test'
        ]);

        $response->assertJson([
            'message'  => "Contact not found"
        ]);
    }

    public function test_getAddressByIdFailedAddressId_notFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();
        $response = $this->get('/api/contacts/' . $address->contact_id . '/addresses/' . $address->id + 1, [
            'Authorization' => 'test'
        ]);

        $response->assertJson([
            'message'  => "Address not found"
        ]);
    }

    public function test_getAddressByIdFailedUnauthorized()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();
        $response = $this->get('/api/contacts/' . $address->contact_id . '/addresses/' . $address->id, [
            'Authorization' => 'salah token'
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'errors' => [
                'message' => [
                    'Unauthorized'
                ]
            ]
        ]);
    }

    public function test_updateAddressSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();
        $response = $this->put('/api/contacts/' . $address->contact_id . '/addresses/' . $address->id, [
            'street' => 'JL test',
            'city' => 'test city',
            'province' => 'north test',
            'country' => 'test country',
            'postal_code' => '953785'
        ], [
            'Authorization' => 'test'
        ]);

        $response->assertJson([
            'data' => [
                'id' => 1,
                'street' => 'JL test',
                'city' => 'test city',
                'province' => 'north test',
                'country' => 'test country',
                'postal_code' => '953785'
            ]
        ]);
    }
    public function test_updateAddressFailedContactId_notFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();
        $response = $this->put('/api/contacts/' . $address->contact_id + 1 . '/addresses/' . $address->id, [
            'street' => 'JL test',
            'city' => 'test city',
            'province' => 'north test',
            'country' => 'test country',
            'postal_code' => '953785'
        ], [
            'Authorization' => 'test'
        ]);

        $response->assertJson([
            'message' => "Contact not found"
        ]);
    }

    public function test_updateAddressFailedAddressId_notFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();
        $response = $this->put('/api/contacts/' . $address->contact_id . '/addresses/' . $address->id + 1, [
            'street' => 'JL test',
            'city' => 'test city',
            'province' => 'north test',
            'country' => 'test country',
            'postal_code' => '953785'
        ], [
            'Authorization' => 'test'
        ]);

        $response->assertJson([
            'message' => "Address not found"
        ]);
    }

    public function test_deleteAddressSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();
        $response = $this->delete('/api/contacts/' . $address->contact_id . '/addresses/' . $address->id, [], [
            'Authorization' => 'test'
        ]);

        $response->assertStatus(200);
    }

    public function test_deleteAddressFailedContactId_notFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();
        $response = $this->delete('/api/contacts/' . $address->contact_id + 1 . '/addresses/' . $address->id, [], [
            'Authorization' => 'test'
        ]);

        $response->assertJson([
            'message' => "Contact not found"
        ]);
    }

    public function test_deleteAddressFailedAddressId_notFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();
        $response = $this->delete('/api/contacts/' . $address->contact_id . '/addresses/' . $address->id + 1, [], [
            'Authorization' => 'test'
        ]);

        $response->assertJson([
            'message' => "Address not found"
        ]);
    }


    // test get list address

}
