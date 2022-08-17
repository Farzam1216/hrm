<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\EmployeeEmploymentStatus;

class UpdateEmployementStatusForEmployee
{
    public function execute($request)
    {
        $employeeEmploymentStatusId = $request->segment(5, '');
        EmployeeEmploymentStatus::find($employeeEmploymentStatusId)->update([
            'effective_date' => $request->effective_date,
            'employment_status_id' => $request->employment_status,
            'comment' => $request->comment,
        ]);
    }
}
