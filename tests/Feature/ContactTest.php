<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Contact;
use Database\Seeders\UserSeeder;
use Database\Seeders\SearchSeeder;
use Database\Seeders\ContactSeeder;
use Illuminate\Support\Facades\Log;
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


    public function test_getContactSuccess()
    {

        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $user = User::query()->limit(1)->first();

        $response = $this->get('/api/contacts/' . $user->id, [
            "Authorization" => "test"
        ]);
        $response->assertJson(
            [
                "data" => [
                    "firstname" => "John",
                    "lastname" => "Doe",
                    "email" => "test123@test.com",
                    "phone" => "1234567890"
                ]
            ]
        );
    }
    public function test_GetContactNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();
        $response = $this->get('/api/contacts/' . ($contact->id + 1), [
            "Authorization" => "test"
        ]);
        $response->assertStatus(404);
        $response->assertJson([
            "errors" => [
                "message" => ["not found"]
            ]
        ]);
    }

    public function test_GetOtherContact()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $response = $this->get("/api/contacts/$contact->id", [
            "Authorization" => "test2"
        ]);
        $response->assertStatus(404);
        $response->assertJson([
            "errors" => [
                "message" => [
                    "not found"
                ]
            ]
        ]);
    }


    public function test_UpdateContactSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $authorization = ["Authorization" => "test"];
        $data = [
            "firstname" => "test_ganti1",
            "lastname" => "test_ganti1",
            "email" => "test_ganti@test.com",
            "phone" => "00000000"
        ];

        $response = $this->put("/api/contacts/$contact->id", $data, $authorization);

        $response->assertJson([
            "data" => [
                "firstname" => "test_ganti1",
                "lastname" => "test_ganti1",
                "email" => "test_ganti@test.com",
                "phone" => "00000000"
            ]
        ]);
    }


    public function test_UpdateWithSomeEmptyData()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $authorization = ["Authorization" => "test"];
        $data = [
            // "firstname" => "",
            // "lastname" => "",
            "email" => "test_ganti@test.com",
            "phone" => "00000000"
        ];

        $response = $this->put("/api/contacts/$contact->id", $data, $authorization);

        $response->assertJson([
            "data" => [

                "firstname" => "John",
                "lastname" => "Doe",
                "email" => "test_ganti@test.com",
                "phone" => "00000000"
            ]
        ]);
    }

    public function test_UpdateWithEmptyData()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $authorization = ["Authorization" => "test"];
        $data = [
            // "firstname" => '',
            // "lastname" => '',
            // "email" => '',
            // "phone" => ''
        ];

        $response = $this->put("/api/contacts/$contact->id", $data, $authorization);

        $response->assertJson([
            "data" => [

                "firstname" => "John",
                "lastname" => "Doe",
                "email" => "test123@test.com",
                "phone" => "1234567890"
            ]
        ]);
    }


    public function test_UpdateContactFailed_DataNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $authorization = ["Authorization" => "test"];
        $data = [
            "firstname" => "test_ganti1",
            "lastname" => "test_ganti1",
            "email" => "test_ganti@test.com",
            "phone" => "00000000"
        ];

        $response = $this->put("/api/contacts/" . ($contact->id + 1), $data, $authorization);
        $response->assertJson([
            "errors" => [
                "message" => [
                    "not found"
                ]
            ]
        ]);
    }

    public function test_UpdateContactFailed_AccessOtherData()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $authorization = ["Authorization" => "test2"];
        $data = [
            "firstname" => "test_ganti1",
            "lastname" => "test_ganti1",
            "email" => "test_ganti@test.com",
            "phone" => "00000000"
        ];

        $response = $this->put("/api/contacts/" . $contact->id, $data, $authorization);
        $response->assertJson([
            "errors" => [
                "message" => [
                    "not found"
                ]
            ]
        ]);
    }

    public function test_DeleteContact()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();
        $id = $contact->id;

        $response = $this->delete('api/contacts/' . $id, headers: [
            "Authorization" => "test"
        ]);

        $response->assertJson([
            "data" => true
        ]);
        
    }

    public function test_SearchByFirstName()
    {
        $this->seed([UserSeeder::class, SearchSeeder::class]);
        $response = $this->get('/api/contacts?name=first', [
            "Authorization" => "test"
        ])->assertStatus(200)
            ->json();
        Log::info(json_encode($response, JSON_PRETTY_PRINT));
        // Log::info($response->getContent());
        $this->assertCount(10, $response['data']);
        $this->assertEquals(20, $response['meta']['total']);
    }
    public function test_SearchByLastName()
    {
        $this->seed([UserSeeder::class, SearchSeeder::class]);
        $response = $this->get('/api/contacts?name=last', [
            "Authorization" => "test"
        ])->assertStatus(200)
            ->json();
        Log::info(json_encode($response, JSON_PRETTY_PRINT));
        // Log::info($response->getContent());
        $this->assertCount(10, $response['data']);
        $this->assertEquals(20, $response['meta']['total']);
    }


    public function test_SearchByEmail()
    {
        $this->seed([UserSeeder::class, SearchSeeder::class]);
        $response = $this->get('/api/contacts?email=test', [
            "Authorization" => "test"
        ])->assertStatus(200)->json();

        Log::info(json_encode($response, JSON_PRETTY_PRINT));
        $this->assertCount(10, $response['data']);
        $this->assertEquals(20, $response['meta']['total']);
    }
    public function test_SearchByPhone()
    {
        $this->seed([UserSeeder::class, SearchSeeder::class]);
        $response = $this->get('/api/contacts?phone=11111', [
            "Authorization" => "test"
        ])->assertStatus(200)->json();

        Log::info(json_encode($response, JSON_PRETTY_PRINT));
        $this->assertCount(10, $response['data']);
        $this->assertEquals(20, $response['meta']['total']);
    }

    public function test_SearchingWithPage()
    {
        $this->seed([UserSeeder::class, SearchSeeder::class]);
        $response = $this->get('/api/contacts?size=5&page=2', [
            "Authorization" => "test"
        ])->assertStatus(200)->json();

        Log::info(json_encode($response, JSON_PRETTY_PRINT));
        $this->assertCount(5, $response['data']);
        $this->assertEquals(2, $response['meta']['current_page']);
        $this->assertEquals(20, $response['meta']['total']);
    }
}
