<?php


namespace App\Domain\Attendance\Actions;


use App\Domain\Attendance\Models\EmployeeAttendanceComments;
use Illuminate\Support\Facades\Auth;

class StoreEmployeeAttendanceComment
{
    /**
     * add available plans in benefitGroup
     * @param $request
     */
    public function execute($request,$employeeAttendanceID)
    {
        $comment = new EmployeeAttendanceComments();
        $comment->employee_attendance_id = $employeeAttendanceID;
        $comment->comment = $request['comment'];
        $comment->comment_added_by = Auth::user()->id;
        if($comment->save()){
            $result = true;
        }else{
            $result = false;
        }
        return $result;
    }
}
