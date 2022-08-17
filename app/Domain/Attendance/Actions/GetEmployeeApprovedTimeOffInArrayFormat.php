<?php


namespace App\Domain\Attendance\Actions;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class GetEmployeeApprovedTimeOffInArrayFormat
{
    public function execute($emp, $attendanceCollections)
    {
        foreach ($emp['assignTimeOffType'] as $timeOffType) { // Get Time off type for employee
            foreach ($timeOffType['requestTimeOff'] as $requestTimeOff) { // Get approved time offs of employee
                $dateTo = Carbon::parse($requestTimeOff->to)->format('Y-m-d');
                $dateFrom = Carbon::parse($requestTimeOff->from)->format('Y-m-d');
                $periods = \Carbon\CarbonPeriod::create($dateTo, $dateFrom);
                foreach ($periods as $key => $period) {
                    $dates[$key+1] = $period->format('Y-m-d');
                }

                foreach($dates as $date) {
                    if (isset($attendanceCollections[$date])) {
                        $attendanceCollections[$date] = [
                            'id' => $attendanceCollections[$date]['id'],
                            'time_in' => $attendanceCollections[$date]['time_in'],
                            'time_out' => $attendanceCollections[$date]['time_out'],
                            'time_in_status' => $attendanceCollections[$date]['time_in_status'],
                            'attendance_status' => 'Paid Time Off ('.$timeOffType->timeOffType->time_off_type_name.')',
                            'reason_for_leaving' => $attendanceCollections[$date]['reason_for_leaving'],
                            'employee_id' => $attendanceCollections[$date]['employee_id'],
                            'created_at' => Carbon::parse($date)->format('Y-m-d')
                        ];
                    }
                }
            }
        }

        return $attendanceCollections;
    }
}
