<?php

namespace App\Domain\Attendance\Actions;

use App\Domain\Attendance\Models\ImportEmployeeAttendance;
use App\Domain\Employee\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Domain\Employee\Actions\AssignOnboardingTasksToNewEmployee;

class NewTemporaryImportEmployeeAttendance implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $employeeValid = Employee::where('email',$row['employee_email'])->first();
        if($employeeValid != null) {
            $employeeAttendance = new ImportEmployeeAttendance([
                'employee_email' => $row['employee_email'],
                'time_in' => $row['time_in'],
                'time_out' => $row['time_out'],
                'date' => $row['date'],
            ]);
            return $employeeAttendance;
        }else{
            return false;
        }

    }
}
