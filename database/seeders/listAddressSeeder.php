<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class listAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        for ($i = 0; $i < 10; $i++) {
            Address::create([
                'contact_id' => 1,
                'street' => 'JL test' . $i,
                'city' => 'test city' . $i,
                'province' => 'north test' . $i,
                'country' => 'test country' . $i,
                'postal_code' => '953785' . $i
            ]);
        }
    }
}
