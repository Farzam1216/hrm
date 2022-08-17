<?php


namespace App\Domain\Attendance\Actions;

use App\Domain\Attendance\Models\EmployeeAttendance;
use Illuminate\Support\Facades\Session;

class DeleteEmployeeMonthlyAttendance
{
    public function execute($request)
    {
        $employeeAttendance = EmployeeAttendance::where('employee_id',$request->employee_id)->whereMonth('created_at','=', $request->month)->whereYear('created_at','=', $request->year)->get();
        if ($employeeAttendance->isNotEmpty()) {
            foreach($employeeAttendance as $attendance)
            {
                $attendance->delete();
            }
            Session::flash('success', 'Employee monthly attendance deleted successfully.');
        }
        else{
            Session::flash('error', 'Employee attendance not found.');
        }
        
    }
}
