<?php

use App\Domain\Employee\Models\Location as Location;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        for ($i=0; $i<=10; $i++):
            $location = Location::create([
                // 'name' => $faker->company,
                // 'address' => $faker->address,
                // 'timing_start' => $faker->time,
                // 'timing_off' => $faker->time,
                // 'weekend'=> '["Saturday","Sunday"]',
                //
                'name' => $faker->company,
                'street_1'=> $faker->streetAddress,
                'street_2'=> $faker->streetAddress,
                'city'=> $faker->city,
                'state'=> $faker->state,
                'zip_code'=> $faker->numberBetween(10000, 99999),
                'country'=> $faker->country,
                'phone_number'=> $faker->phoneNumber,
                'timezone'=> $faker->timezone,
            ]);
        endfor;
    }
}
