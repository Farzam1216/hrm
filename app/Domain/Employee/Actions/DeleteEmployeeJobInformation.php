<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\EmployeeJob;

class DeleteEmployeeJobInformation
{
    public function execute($request)
    {
        $employeeJobId = $request->segment(5, '');
        EmployeeJob::find($employeeJobId)->delete();
    }
}
