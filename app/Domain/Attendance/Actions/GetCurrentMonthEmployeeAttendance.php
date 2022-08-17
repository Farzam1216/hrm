<?php


namespace App\Domain\Attendance\Actions;


use App\Domain\Attendance\Models\EmployeeAttendance;
use App\Domain\Attendance\Models\WorkSchedule;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class GetCurrentMonthEmployeeAttendance
{
    /**
     * add available plans in benefitGroup
     * @param $request
     */
    public function execute($id)
    {
        $employeeAttendanceToday = EmployeeAttendance::where('employee_id',$id)->with('comments.employee')->whereDate('created_at', Carbon::today())->orderBy('created_at','desc')->first();
        $employeeAllTodaysAttendanceToday = EmployeeAttendance::where('employee_id',$id)->with('comments.employee')->whereDate('created_at', Carbon::today())->get();
        $countAttendanceToday = EmployeeAttendance::where('employee_id',$id)->whereDate('created_at', Carbon::today())->count();
        $employeeAttendance = EmployeeAttendance::where('employee_id',$id)->with('comments.employee')
            ->whereBetween('created_at',[Carbon::now()->startOfMonth(),Carbon::now()])->orderBy('created_at','desc')->get();
        $data = [
            'employeeAttendance' => $employeeAttendance,
            'employeeAttendanceToday' => $employeeAttendanceToday,
            'employeeAllTodaysAttendanceToday' => $employeeAllTodaysAttendanceToday,
            'countAttendanceToday' => $countAttendanceToday,
        ];
        return $data;
    }
}
