<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Employee;
use Carbon\Carbon;
use Session;

class GetEmployeeAttendanceAndWorkSchedule
{
    public function execute($request)
    {
        $selectedDate = Carbon::parse('1-'.$request->month.'-'.$request->year);
        $monthEndDate = Carbon::now()->year($selectedDate->year)->month($selectedDate->month)->endOfMonth()->format("d");
        $employee = Employee::where('id', $request->employee_id)->with([
            'employeeAttendance',
            'EmployeeWorkSchedule',
            'assignTimeOffType',
            'assignTimeOffType.timeOffType',
            'assignTimeOffType.requestTimeOff' => function ($query) {$query->where('status', 'approved');}
        ])->get();
        
        if ($employee[0]['EmployeeWorkSchedule']) {
            $nonWorkingDays = explode(',', $employee[0]['EmployeeWorkSchedule']->non_working_days);

            $employeeAttendance = [];
            for($day = 1; $day <= $monthEndDate;){
                $dayName = Carbon::now()->year($selectedDate->year)->month($selectedDate->month)->day($day)->format("l");
                if (in_array(strToLower($dayName), $nonWorkingDays)) {
                    $employeeAttendance[$day] = [
                        'time_in' => '',
                        'time_out' => '',
                        'time_in_status' => '',
                        'attendance_status' => 'holiday',
                        'day' => $dayName,
                        'reason_for_leaving' => '',
                        'created_at' => Carbon::now()->year($selectedDate->year)->month($selectedDate->month)->day($day)->format("Y-m-d"),
                    ];
                } else {
                    $employeeAttendance[$day] = [
                        'time_in' => '',
                        'time_out' => '',
                        'time_in_status' => '',
                        'attendance_status' => 'absent',
                        'day' => $dayName,
                        'reason_for_leaving' => '',
                        'created_at' => Carbon::now()->year($selectedDate->year)->month($selectedDate->month)->day($day)->format("Y-m-d"),
                    ];
                }
                $day++;
            }

            foreach ($employee as $emp){
                foreach ($emp['employeeAttendance'] as $attendance) {
                    if ($attendance->created_at->year == $selectedDate->year && $attendance->created_at->month == $selectedDate->month) {
                        $employeeAttendance[$attendance->created_at->day] = [
                            'time_in' => $attendance->time_in,
                            'time_out' => $attendance->time_out,
                            'time_in_status' => $attendance->time_in_status,
                            'attendance_status' => $attendance->attendance_status,
                            'day' => Carbon::now()->year($selectedDate->year)->month($selectedDate->month)->day($attendance->created_at->day)->format("l"),
                            'reason_for_leaving' => $attendance->reason_for_leaving,
                            'created_at' => Carbon::now()->day($attendance->created_at->day)->month($attendance->created_at->month)->year($attendance->created_at->year)->format("Y-m-d"),
                        ];
                    }
                }
            }


            foreach ($employee as $emp){
                foreach ($emp['assignTimeOffType'] as $timeOffType) {
                    foreach ($timeOffType['requestTimeOff'] as $requestTimeOff) {
                        $dateTo = Carbon::parse($requestTimeOff->to)->format('Y-m-d');
                        $dateFrom = Carbon::parse($requestTimeOff->from)->format('Y-m-d');
                        $periods = \Carbon\CarbonPeriod::create($dateTo, $dateFrom);
                        foreach ($periods as $key => $period) {
                            $dates[$key+1] = $period;
                        }

                        foreach($dates as $date) {
                            if ($date->month == $selectedDate->month && $date->year == $selectedDate->year) {
                                if (isset($employeeAttendance[$date->day])) {
                                    $employeeAttendance[$date->day] = [
                                        'time_in' => $employeeAttendance[$date->day]['time_in'],
                                        'time_out' => $employeeAttendance[$date->day]['time_out'],
                                        'time_in_status' => $employeeAttendance[$date->day]['time_in_status'],
                                        'attendance_status' => 'Paid Time Off ('.$timeOffType->timeOffType->time_off_type_name.')',
                                        'day' => Carbon::now()->year($selectedDate->year)->month($selectedDate->month)->day($date->day)->format("l"),
                                        'reason_for_leaving' => $employeeAttendance[$date->day]['reason_for_leaving'],
                                        'created_at' => Carbon::parse($date)
                                    ];
                                }
                            }
                        }
                    }
                }
            }
            
            return $data = [
                'employee' => $employee,
                'employeeAttendance' => $employeeAttendance,
                'selectedDate' => $selectedDate
            ];
        } else {
            Session::flash('error', trans('language.Work schedule is not assigned to employee'));
            return false;
        }
    }
}
