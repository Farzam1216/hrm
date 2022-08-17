<?php

namespace App\Domain\Attendance\Actions;

use App\Domain\Attendance\Models\EmployeeAttendance;
use Illuminate\Database\Eloquent\Model;

class GetEmployeeAttendanceHistoryById
{
    public function execute($id)
    {
        $employeeAttendance = EmployeeAttendance::where('id',$id)->with(['employee', 'attendanceHistory', 'attendanceHistory.employee'])->first();
        return $employeeAttendance;
    }
}
