<?php

namespace App\Domain\Attendance\Actions;

use Carbon\Carbon;
use App\Domain\Employee\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Domain\Attendance\Actions\GetEmployeeAttendance;
use App\Domain\Attendance\Actions\GetEmployeeFilteredAttendance;

class GetAllEmployeeAttendance
{
    public function execute($request)
    {
        $attendanceCollections = '';
        $employee = Employee::where('id', $request->id)->with([
            'employeeAttendance',
            'employeeAttendance.comments.employee',
            'EmployeeWorkSchedule',
            'employeeHolidays',
            'employeeHolidays.holiday',
            'assignTimeOffType',
            'assignTimeOffType.timeOffType',
            'assignTimeOffType.requestTimeOff' => function ($query) {$query->where('status', 'approved');}
        ])->get();


        foreach ($employee as $emp) {
            $hiring_date = Carbon::parse($emp->joining_date);

            if (isset($emp['EmployeeWorkSchedule']->non_working_days)) {
                $nonWorkingDays = explode(',', $emp['EmployeeWorkSchedule']->non_working_days);

                if (isset($request->date)) {
                    $attendanceCollections = (new GetEmployeeFilteredAttendance())->execute($request, $emp, $hiring_date, $nonWorkingDays);
                }

                if (!isset($request->date)) {
                    $attendanceCollections = (new GetEmployeeAttendance())->execute($emp, $hiring_date, $nonWorkingDays);
                }
            }
        }

        return $data = [
            'attendanceCollections' => $attendanceCollections,
            'employee' => $employee
        ];
    }
}
