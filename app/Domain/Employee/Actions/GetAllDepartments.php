<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Department;

class GetAllDepartments
{
    public function execute()
    {
        return Department::all();
    }
}
