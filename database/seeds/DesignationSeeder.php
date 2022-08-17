<?php

use App\Domain\Employee\Models\Designation;
use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        for ($i=0; $i<=5; $i++) {
            Designation::create([
                'designation_name'=>$faker->jobTitle,
                'status' => 1,
            ]);
        }
    }
}
