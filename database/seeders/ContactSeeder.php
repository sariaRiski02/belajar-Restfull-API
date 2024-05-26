<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Contact;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $user = User::where('username', 'test')->first();
        Contact::create([
            "firstname" => "John",
            "lastname" => "Doe",
            "email" => "test123@test.com",
            "phone" => "1234567890",
            "user_id" => $user->id
        ]);
    }
}
