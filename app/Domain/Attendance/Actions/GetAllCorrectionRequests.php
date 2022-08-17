<?php

namespace App\Domain\Attendance\Actions;

use Illuminate\Database\Eloquent\Model;
use App\Domain\Attendance\Models\EmployeeAttendance;
use App\Domain\Attendance\Models\EmployeeAttendanceCorrection;

class GetAllCorrectionRequests
{
    /**
     * add available plans in benefitGroup
     * @param $request
     */
    public function execute()
    {
        $attendanceCorrections = EmployeeAttendanceCorrection::with('employee')->get();
        return $attendanceCorrections;
    }
}
