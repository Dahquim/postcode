<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 250; $i++) {
            DB::table('stores')->insert([
                'name' => $faker->company,
                'coords' => [
                    'latitude' => $faker->latitude(50.1, 60.1),
                    'longitude' => $faker->longitude(-7.6, 1.8)
                ],
                'status' => $faker->randomElement(['open', 'closed']),
                'type' => $faker->randomElement(['takeaway', 'restaurant', 'shop']),
                'max_delivery_distance' => $faker->numberBetween(1, 20),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
