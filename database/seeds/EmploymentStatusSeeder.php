<?php

use App\Domain\Employee\Models\EmploymentStatus;
use Illuminate\Database\Seeder;

class EmploymentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmploymentStatus::Create([
                'employment_status' => 'Full-Time',
                'description'=> '8 hours Daily',
                'status'    => 1,
            ]);

        EmploymentStatus::Create([
                'employment_status' => 'Part-Time',
                'description'=> 'Less than 8 hours Daily',
                'status'    => 1,
            ]);
    }
}
