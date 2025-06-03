<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Factory as Faker;

class TestUserGeneration extends Seeder
{
    /*
     * Generating test users data, into the database
     * Installing propaganistas/laravel-phone adding functionallity for validating phone numbers
     */
    public function run()
    {
        $faker = Faker::create();
        
        foreach (range(1, 45) as $i) {
            User::create([
                'FullName' => $faker->name,
                'E-Mail' => $faker->unique()->safeEmail,
                'Phone' => $faker->unique()->phoneNumber,
                'PositionId' => $faker->numberBetween(1,4),
                'Photo' => env('APP_URL') . '/image/default' . $faker->numberBetween(0,3).'.jpg',
            ]);
        }
    }
}
