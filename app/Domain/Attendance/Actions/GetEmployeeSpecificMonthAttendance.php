<?php


namespace App\Domain\Attendance\Actions;

use App\Domain\Attendance\Models\EmployeeAttendance;

class GetEmployeeSpecificMonthAttendance
{
    /**
     * add available plans in benefitGroup
     * @param $request
     */
    public function execute($request)
    {
        $year = date('Y', strtotime($request->date));
        $month = date('m', strtotime($request->date));
        $employeeAttendance = EmployeeAttendance::where('employee_id',$request->id)->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $year)->with('comments.employee')->orderBy('created_at','desc')->get();
        return response()->json($employeeAttendance);
    }
}
