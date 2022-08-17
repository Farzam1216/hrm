<?php


namespace App\Domain\Attendance\Actions;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class GetEmployeeAttendanceInArrayFormat
{
    public function execute($emp, $attendanceCollections)
    {
        foreach ($emp['employeeAttendance'] as $attendance) {
            $dayName = Carbon::now()->year($attendance->created_at->year)->month($attendance->created_at->month)->day($attendance->created_at->day)->format("l");
            
            if (isset($attendanceCollections[$attendance->created_at->format('Y-m-d')])) {
                if (!$attendanceCollections[$attendance->created_at->format('Y-m-d')]['time_in']) {
                    $attendanceCollections[$attendance->created_at->format('Y-m-d')] = [
                        'id' => $attendance->id,
                        'time_in' => $attendance->time_in,
                        'time_out' => $attendance->time_out,
                        'time_in_status' => $attendance->time_in_status,
                        'attendance_status' => $attendance->attendance_status,
                        'day' => $dayName,
                        'reason_for_leaving' => $attendance->reason_for_leaving,
                        'employee_id' => $attendance->employee_id,
                        'created_at' => $attendance->created_at->format('Y-m-d')
                    ];
                }

                if ($attendanceCollections[$attendance->created_at->format('Y-m-d')]['time_in']) {
                    $attendanceCollections[$attendance->created_at->format('Y-m-d')] = [
                        'id' => $attendanceCollections[$attendance->created_at->format('Y-m-d')]['id'],
                        'time_in' => $attendanceCollections[$attendance->created_at->format('Y-m-d')]['time_in'],
                        'time_out' => $attendance->time_out,
                        'time_in_status' => $attendanceCollections[$attendance->created_at->format('Y-m-d')]['time_in_status'],
                        'attendance_status' => $attendance->attendance_status,
                        'day' => $dayName,
                        'reason_for_leaving' => $attendance->reason_for_leaving,
                        'employee_id' => $attendanceCollections[$attendance->created_at->format('Y-m-d')]['employee_id'],
                        'created_at' => $attendanceCollections[$attendance->created_at->format('Y-m-d')]['created_at']
                    ];
                }
            }
        }

        return $attendanceCollections;
    }
}
