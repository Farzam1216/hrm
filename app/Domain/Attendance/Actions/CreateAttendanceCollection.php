<?php

namespace App\Domain\Attendance\Actions;

use Illuminate\Database\Eloquent\Model;
use App\Domain\Attendance\Actions\CheckIfGivenDayIsNonWorkingDay;

class CreateAttendanceCollection
{    /**
     * add available plans in benefitGroup
     * @param $request
     */
    public function execute($date, $dayName, $nonWorkingDays, $attendanceCollections)
    {
        $checkNonWorkingDay = (new CheckIfGivenDayIsNonWorkingDay())->execute($dayName, $nonWorkingDays);
        
        if ($checkNonWorkingDay == true) {
            $attendanceCollections[$date->format('Y-m-d')] = [
                'id' => '',
                'time_in' => '',
                'time_out' => '',
                'time_in_status' => '',
                'attendance_status' => 'Weekend',
                'day' => $dayName,
                'reason_for_leaving' => '',
                'employee_id' => '',
                'created_at' => $date->format('Y-m-d')
            ];
        } else {
            $attendanceCollections[$date->format('Y-m-d')] = [
                'id' => '',
                'time_in' => '',
                'time_out' => '',
                'time_in_status' => '',
                'attendance_status' => 'Absent',
                'day' => $dayName,
                'reason_for_leaving' => '',
                'employee_id' => '',
                'created_at' => $date->format('Y-m-d')
            ];
        }

        return $attendanceCollections;
    }
}
