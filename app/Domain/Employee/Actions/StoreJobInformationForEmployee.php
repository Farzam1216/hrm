<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\EmployeeJob;

class StoreJobInformationForEmployee
{
    public function execute($request, $id)
    {
        $data = $request->all();
        EmployeeJob::create([
            'effective_date' => $data['effective_date'],
            'designation_id' => $data['designation_id'],
            'report_to' => $data['report_to'],
            'department_id' => $data['department_id'],
            'division_id' => $data['division_id'],
            'location_id' => $data['location_id'],
            'employee_id' => $id
        ]);
    }
}
