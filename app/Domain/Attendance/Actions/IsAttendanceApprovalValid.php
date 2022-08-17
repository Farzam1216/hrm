<?php


namespace App\Domain\Attendance\Actions;


use App\Domain\Attendance\Models\AttendanceApproval;
use App\Domain\Attendance\Models\EmployeeAttendance;
use App\Domain\Attendance\Models\WorkSchedule;
use App\Domain\Employee\Models\Employee;
use Carbon\Carbon;
use Carbon\Traits\Creator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class IsAttendanceApprovalValid
{
    /**
     * add available plans in benefitGroup
     * @param $request
     */
    public function execute($id)
    {
        $now = Carbon::now();
        $lastDayofMonth = Carbon::parse($now)->endOfMonth()->toDateString();
        $valid = false;
        if($now->toDateString() == $lastDayofMonth){
            $approvalValid = AttendanceApproval::where('employee_id',$id)->where('month',$now->format('F').'-'.$now->format('Y'))->first();
            if($approvalValid == null) {
                $valid = true;
            }
        }else{
            $lastMonthName = $now->subMonth()->format('F');
            $lastMonthYear = $now->subMonth()->format('Y');
            $lasMonthAttendance = EmployeeAttendance::whereBetween('created_at',[$now->subMonth()->startOfMonth(),$now->subMonth()->endOfMonth()])->get();
            if (count($lasMonthAttendance) > 0) {
                $approvalValid = AttendanceApproval::where('employee_id', $id)->where('month', $lastMonthName . '-' . $lastMonthYear)->first();
                if ($approvalValid == null) {
                    $valid = true;
                }
            }
        }
        return $valid;

    }
}
