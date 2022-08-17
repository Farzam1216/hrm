<?php

namespace App\Domain\Employee\Actions;
use App\Domain\Employee\Models\Employee;

class GetEmployeeNonWorkingDays
{
    public function execute($id)
    {
        $employee = Employee::where('id', $id)->with([ 'EmployeeWorkSchedule'])->first();
        if ($employee['EmployeeWorkSchedule']) {
            $nonWorkingDays = explode(',', $employee['EmployeeWorkSchedule']->non_working_days);
            return $nonWorkingDays;
        } else {
            return $employeeWorkSchedule = false;
        }
    }
}
