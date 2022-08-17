<?php

use App\Domain\Employee\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('fr_FR');
        for ($i=0; $i<=5; $i++) {
            Department::Create([
                'department_name' => $faker->departmentName,
                'status'    => 'Active',
            ]);
        }
    }
}
