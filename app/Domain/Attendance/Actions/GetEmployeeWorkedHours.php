<?php


namespace App\Domain\Attendance\Actions;


use App\Domain\Attendance\Models\EmployeeAttendance;
use App\Domain\Attendance\Models\WorkSchedule;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class GetEmployeeWorkedHours
{
    /**
     * add available plans in benefitGroup
     * @param $request
     */
    public function execute($id)
    {
        $employeeWorkedToday = 0;
        $employeeWorkedThisWeek = 0;
        $employeeWorkedThisMonth = 0;
        $employeeAttendanceToday = EmployeeAttendance::where('employee_id', $id)
            ->whereDate('created_at', Carbon::today())->get();
        if ($employeeAttendanceToday != null) {
            foreach ($employeeAttendanceToday as $attendance) {
                if ($attendance->time_out == null) {
                    $start = Carbon::parse($attendance->time_in);
                    $end = Carbon::now();
                    $minutes = $end->diffInMinutes($start);
                    $employeeWorkedToday += round($minutes / 60, 2);
                } else {
                    $start = Carbon::parse($attendance->time_in);
                    $end = Carbon::parse($attendance->time_out);
                    $minutes = $end->diffInMinutes($start);
                    $employeeWorkedToday += round($minutes / 60, 2);
                }
            }
        }
        $employeeAttendanceThisWeek = EmployeeAttendance::where('employee_id', $id)
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
        if ($employeeAttendanceThisWeek != null) {
            foreach ($employeeAttendanceThisWeek as $employeeAttendanceThisDay) {
                if ($employeeAttendanceThisDay->time_out == null) {
                    $start = Carbon::parse($employeeAttendanceThisDay->time_in);
                    $end = Carbon::now();
                    $minutes = $end->diffInMinutes($start);
                    $employeeWorkedThisWeek += round($minutes / 60, 2);
                } else {
                    $start = Carbon::parse($employeeAttendanceThisDay->time_in);
                    $end = Carbon::parse($employeeAttendanceThisDay->time_out);
                    $minutes = $end->diffInMinutes($start);
                    $employeeWorkedThisWeek += round($minutes / 60, 2);
                }
            }
        }

        $employeeAttendanceThisMonth = EmployeeAttendance::where('employee_id', $id)
            ->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))
            ->get();
        if ($employeeAttendanceThisMonth != null) {
            foreach ($employeeAttendanceThisMonth as $employeeAttendanceThisDate) {
                if ($employeeAttendanceThisDate->time_out == null) {
                    $start = Carbon::parse($employeeAttendanceThisDate->time_in);
                    $end = Carbon::now();
                    $minutes = $end->diffInMinutes($start);
                    $employeeWorkedThisMonth += round($minutes / 60, 2);
                } else {
                    $start = Carbon::parse($employeeAttendanceThisDate->time_in);
                    $end = Carbon::parse($employeeAttendanceThisDate->time_out);
                    $minutes = $end->diffInMinutes($start);
                    $employeeWorkedThisMonth += round($minutes / 60, 2);
                }
            }
        }
        $data = [
            'employeeWorkedToday' => $employeeWorkedToday,
            'employeeWorkedThisWeek' => $employeeWorkedThisWeek,
            'employeeWorkedThisMonth' => $employeeWorkedThisMonth,
        ];

        return $data;

    }
}
