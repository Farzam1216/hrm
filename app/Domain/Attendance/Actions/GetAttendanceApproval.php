<?php


namespace App\Domain\Attendance\Actions;


use App\Domain\Attendance\Models\AttendanceApproval;


class GetAttendanceApproval
{
    /**
     * add available plans in benefitGroup
     * @param $request
     */
    public function execute()
    {
        $data = AttendanceApproval::with('employee','approver','comments')->get();
        return $data;
    }
}
