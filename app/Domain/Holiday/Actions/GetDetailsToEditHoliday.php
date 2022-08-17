<?php

namespace App\Domain\Holiday\Actions;

use App\Domain\Holiday\Models\Holiday;
use App\Domain\Employee\Models\Division;
use App\Domain\Employee\Models\Location;
use App\Domain\Employee\Models\Department;
use App\Domain\Employee\Models\Designation;
use App\Domain\Employee\Models\EmploymentStatus;

class GetDetailsToEditHoliday
{
    /**
     * @return mixed
     */
    public function execute($id)
    {
        $holiday = Holiday::find($id);

        $divisions = Division::all();
        $locations = Location::all();
        $departments = Department::all();
        $designations = Designation::all();
        $employment_statuses = EmploymentStatus::all();

        return $data = [
            'holiday' => $holiday,
            'divisions' => $divisions,
            'locations' => $locations,
            'departments' => $departments,
            'designations' => $designations,
            'employment_statuses' => $employment_statuses
        ];
    }
}
