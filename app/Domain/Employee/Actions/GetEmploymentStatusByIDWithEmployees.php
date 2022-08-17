<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\EmploymentStatus;

class GetEmploymentStatusByIDWithEmployees
{
    public function execute($id)
    {
        return EmploymentStatus::where('id',$id)->with('employees')->first();
    }
}
