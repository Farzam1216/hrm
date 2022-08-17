<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Department as Department;

class GetDepartmentByID
{
    public function execute($id)
    {
        return Department::find($id);
    }
}