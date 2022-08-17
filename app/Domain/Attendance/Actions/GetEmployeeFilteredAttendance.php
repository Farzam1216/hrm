<?php


namespace App\Domain\Attendance\Actions;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Domain\Attendance\Actions\GetAttendanceByEmployeeId;
use App\Domain\Attendance\Actions\CreateAttendanceCollection;
use App\Domain\Attendance\Actions\GetApprovedTimeOffByEmployeeId;
use App\Domain\Attendance\Actions\GetAssignedHolidaysByEmployeeId;
use App\Domain\Attendance\Actions\CheckIfDateIsBetweenHiringAndTodayDate;

class GetEmployeeFilteredAttendance
{
    public function execute($request, $emp, $hiring_date, $nonWorkingDays)
    {
        $now = Carbon::now();
        $attendanceCollections = [];

        $filterRange = explode(' to ', $request->date);
        $start_date = Carbon::parse($filterRange[0]);
        $end_date = Carbon::parse($filterRange[1]);

        for ($year = $start_date->year; $year <= $end_date->year; $year++) {
            for ($month = 1; $month <= 12 ; $month++) {
                $endOfMonth = Carbon::parse($year.'-'.$month.'-01')->endOfMonth();

                for ($day = 1; $day <= $endOfMonth->day; $day++) {
                    $date = Carbon::parse($year.'-'.$month.'-'.$day);
                    $dayName = $date->format("l");

                    $checkHiringAndTodayDate = (new CheckIfDateIsBetweenHiringAndTodayDate())->execute($date, $hiring_date, $now);

                    if ($checkHiringAndTodayDate == true) {
                        if ($date->gte($start_date) && $date->lte($end_date)) {
                            $attendanceCollections = (new CreateAttendanceCollection())->execute($date, $dayName, $nonWorkingDays, $attendanceCollections);
                        }
                    }
                }
            }
        }

        $attendanceCollections = (new GetEmployeeAttendanceInArrayFormat())->execute($emp, $attendanceCollections);

        $attendanceCollections = (new GetEmployeeApprovedTimeOffInArrayFormat())->execute($emp, $attendanceCollections);

        $attendanceCollections = (new GetEmployeeAssignedHolidaysInArrayFormat())->execute($emp, $attendanceCollections);

        return $attendanceCollections;
    }
}
