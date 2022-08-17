<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Employee;

class GetEmployeeFillable
{
    public function execute()
    {
        $employee = new Employee();
        return $employee->getFillable();
    }
}
