<?php


namespace App\Domain\Attendance\Actions;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Domain\Attendance\Actions\GetAttendanceByEmployeeId;
use App\Domain\Attendance\Actions\CreateAttendanceCollection;
use App\Domain\Attendance\Actions\GetApprovedTimeOffByEmployeeId;
use App\Domain\Attendance\Actions\GetAssignedHolidaysByEmployeeId;
use App\Domain\Attendance\Actions\CheckIfDateIsBetweenHiringAndTodayDate;

class GetEmployeeAttendance
{
    public function execute($emp, $hiring_date, $nonWorkingDays)
    {
        $now = Carbon::now();
        $attendanceCollections = [];

        $endOfMonth = Carbon::parse($now->year.'-'.$now->month.'-01')->endOfMonth();

        for ($day = 1; $day <= $endOfMonth->day; $day++) {
            $date = Carbon::parse($now->year.'-'.$now->month.'-'.$day);
            $dayName = $date->format("l");

            $checkHiringAndTodayDate = (new CheckIfDateIsBetweenHiringAndTodayDate())->execute($date, $hiring_date, $now);

            if ($checkHiringAndTodayDate == true) {
                $attendanceCollections = (new CreateAttendanceCollection())->execute($date, $dayName, $nonWorkingDays, $attendanceCollections);
            }
        }

        $attendanceCollections = (new GetEmployeeAttendanceInArrayFormat())->execute($emp, $attendanceCollections);

        $attendanceCollections = (new GetEmployeeApprovedTimeOffInArrayFormat())->execute($emp, $attendanceCollections);

        $attendanceCollections = (new GetEmployeeAssignedHolidaysInArrayFormat())->execute($emp, $attendanceCollections);

        return $attendanceCollections;
    }
}
