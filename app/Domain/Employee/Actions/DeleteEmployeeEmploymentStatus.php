<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\EmployeeEmploymentStatus;

class DeleteEmployeeEmploymentStatus
{
    public function execute($request)
    {
        $employeeEmploymentStatusId = $request->segment(5, '');
        EmployeeEmploymentStatus::find($employeeEmploymentStatusId)->delete();
    }
}
