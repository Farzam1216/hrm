<?php


namespace App\Domain\Attendance\Actions;


use App\Domain\Employee\Models\Employee;
use Illuminate\Database\Eloquent\Model;

class GetAttendanceByDate
{
    /**
     * add available plans in benefitGroup
     * @param $request
     */
    public function execute($employee_id)
    {
        $employee = Employee::where('id', $employee_id)->with('employeeAttendance')->first();
        return $employee;
    }
}
