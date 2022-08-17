<?php


namespace App\Domain\Attendance\Actions;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class GetEmployeeAssignedHolidaysInArrayFormat
{
    public function execute($emp, $attendanceCollections)
    {
        foreach ($emp['employeeHolidays'] as $employeeHoliday) {
            if(strpos($employeeHoliday->holiday->date, " to ") !== false) {
                $dates = explode(" to ", $employeeHoliday->holiday->date);
                $from = Carbon::parse($dates[0])->format('Y-m-d');
                $to = Carbon::parse($dates[1])->format('Y-m-d');
                $periods = \Carbon\CarbonPeriod::create($from, $to);
                foreach ($periods as $key => $period) {
                    $dates[$key++] = $period->format('Y-m-d');
                }
            } else {
                $dates = explode(" to ", $employeeHoliday->holiday->date);
            }

            foreach($dates as $date) {
                if (isset($attendanceCollections[$date])) {
                    $attendanceCollections[$date] = [
                        'id' => $attendanceCollections[$date]['id'],
                        'time_in' => $attendanceCollections[$date]['time_in'],
                        'time_out' => $attendanceCollections[$date]['time_out'],
                        'time_in_status' => $attendanceCollections[$date]['time_in_status'],
                        'attendance_status' => 'Holiday ('.$employeeHoliday->holiday->name.')',
                        'reason_for_leaving' => $attendanceCollections[$date]['reason_for_leaving'],
                        'employee_id' => $attendanceCollections[$date]['employee_id'],
                        'created_at' => Carbon::parse($date)->format('Y-m-d')
                    ];
                }
            }
        }

        return $attendanceCollections;
    }
}
