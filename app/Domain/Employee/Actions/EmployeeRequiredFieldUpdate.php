<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Employee;

class EmployeeRequiredFieldUpdate
{
    /*
     *Updating the Employee data if user has tried to update the Fields that required Approval
     *
     *
     */

    public function execute($id, $data)
    {
        $employee = Employee::where('id', $id)
            ->update($data);
    }
}
