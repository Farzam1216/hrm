<?php

namespace App\Domain\Attendance\Actions;

use App\Domain\Attendance\Models\EmployeeAttendance;
use Illuminate\Database\Eloquent\Model;

class GetEmployeeAttendanceById
{
    public function execute($id)
    {
        $employeeAttendance = EmployeeAttendance::where('id',$id)->with(['employee', 'employee.employeeWorkSchedule'])->first();
        return $employeeAttendance;
    }
}
