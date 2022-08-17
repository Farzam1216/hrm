<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\EmployeeEmploymentStatus;

class StoreEmployementStatusForEmployee
{
    public function execute($request, $id)
    {
        EmployeeEmploymentStatus::create([
            'employee_id' => $id,
            'effective_date' => $request->effective_date,
            'employment_status_id' => $request->employment_status,
            'comment' => $request->comment,
        ]);
    }
}
