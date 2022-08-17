<?php

namespace App\Domain\Holiday\Actions;

use App\Domain\Employee\Models\Division;
use App\Domain\Employee\Models\Location;
use App\Domain\Employee\Models\Department;
use App\Domain\Employee\Models\Designation;
use App\Domain\Employee\Models\EmploymentStatus;

class GetDetailsToCreateHoliday
{
    /**
     * @return mixed
     */
    public function execute()
    {
        $divisions = Division::all();
        $locations = Location::all();
        $departments = Department::all();
        $designations = Designation::all();
        $employment_statuses = EmploymentStatus::all();

        return $data = [
            'divisions' => $divisions,
            'locations' => $locations,
            'departments' => $departments,
            'designations' => $designations,
            'employment_statuses' => $employment_statuses
        ];
    }
}
