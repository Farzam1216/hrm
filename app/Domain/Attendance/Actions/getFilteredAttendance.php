<?php


namespace App\Domain\Attendance\Actions;

use App\Domain\Attendance\Models\EmployeeAttendance;

class getFilteredAttendance
{
    /**
     * add available plans in benefitGroup
     * @param $request
     */
    public function execute($request)
    {
        if ($request->id == 'all') {
            $employeeAttendance = EmployeeAttendance::with('employee')->orderBy('created_at','asc')->get();
        }

        if ($request->id == 'all' && $request->month) {
            $month = $request->month;
            $employeeAttendance = EmployeeAttendance::whereMonth('created_at', '=', $month)->with('employee')->orderBy('created_at','asc')->get();
        }

        if ($request->id == 'all' && $request->year) {
            $year = $request->year;
            $employeeAttendance = EmployeeAttendance::whereYear('created_at', '=', $year)->with('employee')->orderBy('created_at','asc')->get();
        }

        if ($request->id == 'all' && $request->month && $request->year) {
            $year = $request->year;
            $month = $request->month;
            $employeeAttendance = EmployeeAttendance::whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $year)->with('employee')->orderBy('created_at','asc')->get();
        }

        if ($request->id != 'all') {
            $employeeAttendance = EmployeeAttendance::where('employee_id', $request->id)->with('employee')->orderBy('created_at','asc')->get();
        }

        if ($request->id != 'all' && $request->month) {
            $month = $request->month;
            $employeeAttendance = EmployeeAttendance::where('employee_id', $request->id)->whereMonth('created_at', '=', $month)->with('employee')->orderBy('created_at','asc')->get();
        }

        if ($request->id != 'all' && $request->year) {
            $year = $request->year;
            $employeeAttendance = EmployeeAttendance::where('employee_id', $request->id)->whereYear('created_at', '=', $year)->with('employee')->orderBy('created_at','asc')->get();
        }

        if ($request->id != 'all' && $request->month && $request->year) {
            $year = $request->year;
            $month = $request->month;
            $employeeAttendance = EmployeeAttendance::where('employee_id', $request->id)->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $year)->with('employee')->orderBy('created_at','asc')->get();
        }

        return $employeeAttendance;
    }
}
