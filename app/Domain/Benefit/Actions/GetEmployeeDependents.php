<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\EmployeeDependent;

class GetEmployeeDependents
{
    public function execute($employeeID)
    {
        return EmployeeDependent::where('employee_id', $employeeID)->get();
    }
}
