<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\EmployeeJob;

class GetEmployeeJobInformation
{
    public function execute($id)
    {
        return EmployeeJob::with('designation', 'manager', 'department', 'division', 'location')
            ->where('employee_id', $id)->orderBy('effective_date', 'desc')->get();
    }
}
