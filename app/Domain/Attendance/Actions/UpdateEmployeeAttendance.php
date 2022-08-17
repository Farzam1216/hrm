<?php


namespace App\Domain\Attendance\Actions;

use App\Domain\Attendance\Models\EmployeeAttendance;
use App\Domain\Attendance\Models\EmployeeAttendanceHistory;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UpdateEmployeeAttendance
{
    /**
     * add available plans in benefitGroup
     * @param $request
     */
    public function execute($request)
    {
        $time_in = Carbon::parse($request->time_in);
        $time_out = Carbon::parse($request->time_out);
        $timeCheck = false;

        if ($time_out->gte($time_in)) {
            $timeCheck = true;
        } else {
            if ($time_in->format('A') != $time_out->format('A')) {
                $timeCheck = true;
            }
        }

        $employeeAttendance = EmployeeAttendance::where('id', $request->attendance_id)->first();

        if ($timeCheck == true) {
            $employeeAttendance->time_in = Carbon::parse($request->time_in)->format('h:i A');
            $employeeAttendance->time_out = Carbon::parse($request->time_out)->format('h:i A');
            $employeeAttendance->time_in_status = $request->time_in_status;
            $employeeAttendance->attendance_status = $request->attendance_status;
            $employeeAttendance->reason_for_leaving = $request->reason_for_leaving;
            $employeeAttendance->save();

            $employeeAttendanceHistory = EmployeeAttendanceHistory::where('attendance_id', $request->attendance_id)->latest('created_at')->first();

            if ($employeeAttendanceHistory) {
                $employeeAttendanceHistory->changed_by_id = Auth::id();
                $employeeAttendanceHistory->save();
            }

            return $data = [
                'employeeAttendance' => $employeeAttendance,
                'timeCheck' => $timeCheck
            ];
        }

        if ($timeCheck == false) {
            return $data = [
                'employeeAttendance' => $employeeAttendance,
                'timeCheck' => $timeCheck
            ];
        }
    }
}
