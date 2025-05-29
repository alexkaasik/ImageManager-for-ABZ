<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Factory as Faker;

class TestUserGeneration extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        
        foreach (range(1, 45) as $i) {


            User::create([
                'FullName' => $faker->name,
                'E-Mail' => $faker->unique()->safeEmail,
                'Phone' => $faker->unique()->phoneNumber,
                'PositionId' => $faker->numberBetween(1,4),
                'Photo' => 'image/default' . $faker->numberBetween(0,3).'.jpg',
            ]);
        }
    }
}
