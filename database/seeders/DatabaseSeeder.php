<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $role = \App\Models\Role::create(['name' => 'Admin']);
        $country = \App\Models\Country::create(['name' => 'USA', 'code' => 'US', 'phone_code' => '+1', 'currency' => 'USD']);
        $state = \App\Models\State::create(['name' => 'California', 'country_id' => $country->id]);
        $city = \App\Models\City::create(['name' => 'Los Angeles', 'state_id' => $state->id]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
            'country_id' => $country->id,
            'state_id' => $state->id,
            'city_id' => $city->id,
            'gender' => 'M',
            'status' => 'Y',
        ]);
    }
}
