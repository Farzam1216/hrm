<?php

namespace App\Domain\Attendance\Actions;

use App\Domain\Attendance\Actions\StoreAttendanceCorrectionRequestDecisionNotifications;
use App\Domain\Attendance\Models\EmployeeAttendance;
use App\Domain\Attendance\Models\EmployeeAttendanceCorrection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UpdateEmployeeAttendanceFromCorrectionRequest
{
    /**
     * add available plans in benefitGroup
     * @param $request
     */
    public function execute($request, $correction_id)
    {
        $correctionRequest = EmployeeAttendanceCorrection::where('id', $correction_id)->with('employee')->first();

        if ($request->decision == 'approved') {
            $time_ins = explode(',',$correctionRequest->time_in);
            $time_outs = explode(',',$correctionRequest->time_out);
            $time_in_statuses = explode(',',$correctionRequest->time_in_status);
            $reason_for_leavings = explode(',',$correctionRequest->reason_for_leaving);
            $attendance_statuses = explode(',',$correctionRequest->attendance_status);
            $attendance_ids = explode(',',$correctionRequest->attendance_id);
            $remove_attendance_ids = explode(',',$correctionRequest->remove_attendance_id);
            $totalAttendanceEntries = $correctionRequest->total_entries;

            foreach ($remove_attendance_ids as $remove_attendance_id) {
                $removeEmployeeAttendance = EmployeeAttendance::where('id',$remove_attendance_id)->where('employee_id', $request->employee_id)->first();

                if ($removeEmployeeAttendance) {
                    $removeEmployeeAttendance->delete();
                }
            }

            for ($index = 0; $index < $totalAttendanceEntries; $index++) {
                if (isset($attendance_ids[$index])) {
                    $employeeAttendance = EmployeeAttendance::find($attendance_ids[$index]);
                } else {
                    $employeeAttendance = '';
                }

                if ($employeeAttendance) {
                    if($time_ins[$index] != ' ') {
                        $employeeAttendance->time_in = $time_ins[$index];
                    }
                    if($time_outs[$index] != ' ') {
                        $employeeAttendance->time_out = $time_outs[$index];
                    }
                    if($time_in_statuses[$index] != ' ') {
                        $employeeAttendance->time_in_status = $time_in_statuses[$index];
                    }
                    if($reason_for_leavings[$index] != ' ') {
                        $employeeAttendance->reason_for_leaving = $reason_for_leavings[$index];
                    }
                    if($attendance_statuses[$index] != ' ') {
                        $employeeAttendance->attendance_status = $attendance_statuses[$index];
                    } else {
                        $employeeAttendance->attendance_status = 'Absent';
                    }
                    $employeeAttendance->created_at = Carbon::parse($correctionRequest->date.' '.$time_ins[$index]);
                    $employeeAttendance->save();
                }

                if (!$employeeAttendance) {
                    $newEmployeeAttendance = new EmployeeAttendance();
                    if($time_ins[$index] != ' ') {
                        $newEmployeeAttendance->time_in = $time_ins[$index];
                    }
                    if($time_outs[$index] != ' ') {
                        $newEmployeeAttendance->time_out = $time_outs[$index];
                    }
                    if($time_in_statuses[$index] != ' ') {
                        $newEmployeeAttendance->time_in_status = $time_in_statuses[$index];
                    }
                    if($reason_for_leavings[$index] != ' ') {
                        $newEmployeeAttendance->reason_for_leaving = $reason_for_leavings[$index];
                    }
                    if($attendance_statuses[$index] != ' ') {
                        $newEmployeeAttendance->attendance_status = $attendance_statuses[$index];
                    } else {
                        $newEmployeeAttendance->attendance_status = 'Absent';
                    }
                    $newEmployeeAttendance->employee_id = $correctionRequest->employee->id;
                    $newEmployeeAttendance->created_at = Carbon::parse($correctionRequest->date.' '.$time_ins[$index]);
                    $newEmployeeAttendance->save();
                }
            }
        }

        $correctionRequest->status = $request->decision;
        $correctionRequest->save();

        (new StoreAttendanceCorrectionRequestDecisionNotifications())->execute($request->employee_id, Auth::id(), Carbon::parse($request->date), $request->decision);

        return $correctionRequest;
    }
}
