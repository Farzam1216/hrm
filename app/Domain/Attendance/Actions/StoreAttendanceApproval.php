<?php


namespace App\Domain\Attendance\Actions;


use App\Domain\Attendance\Models\AttendanceApproval;
use App\Domain\Attendance\Models\AttendanceApprovelComments;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class StoreAttendanceApproval
{
    /**
     * add available plans in benefitGroup
     * @param $request
     */
    public function execute($request)
    {
        $attendanceApproval = new AttendanceApproval();
        $attendanceApproval->month = $request->month;
        $attendanceApproval->status = $request->status;
        $attendanceApproval->employee_id = $request->employee_id;
        $attendanceApproval->approver_id = Auth::user()->id;
        $attendanceApproval->save();

        if ($request->comment != null) {
            $attendanceApprovalComment = new AttendanceApprovelComments();
            $attendanceApprovalComment->approval_id = $attendanceApproval->id;
            $attendanceApprovalComment->comment = $request->comment;
            $attendanceApprovalComment->save();
        }

    }
}
