<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\EmployeeDependent;

class DeleteEmployeeDependents
{
    /**
     * @param $id
     */
    public function execute($id)
    {
        $employeeDependent = EmployeeDependent::find($id);
        $employeeDependent->delete();
    }
}
