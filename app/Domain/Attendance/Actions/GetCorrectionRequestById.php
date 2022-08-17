<?php

namespace App\Domain\Attendance\Actions;

use Illuminate\Database\Eloquent\Model;
use App\Domain\Attendance\Models\EmployeeAttendance;
use App\Domain\Attendance\Models\EmployeeAttendanceCorrection;

class GetCorrectionRequestById
{
    /**
     * add available plans in benefitGroup
     * @param $request
     */
    public function execute($id)
    {
        $correctionRequest = EmployeeAttendanceCorrection::where('id', $id)->with(['employee', 'employee.employeeAttendance'])->first();

        return $correctionRequest;
    }
}
