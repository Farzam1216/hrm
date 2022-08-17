<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\EmployeeEmploymentStatus;

class GetEmployeeEmploymentStatuses
{
    public function execute($id)
    {
        return EmployeeEmploymentStatus::with('employee', 'employmentStatus')
            ->where('employee_id', $id)->orderBy('effective_date', 'desc')->get();
    }
}
