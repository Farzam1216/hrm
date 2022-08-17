<?php


namespace App\Domain\Attendance\Actions;


use App\Domain\Attendance\Models\EmployeeAttendance;
use App\Domain\Attendance\Models\WorkSchedule;
use App\Domain\Employee\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EmployeeClockOutisValid
{
    /**
     * add available plans in benefitGroup
     * @param $request
     */
    public function execute($id)
    {
        $now = Carbon::now();
        $valid = false;
        $user = Employee::find($id);
        if(isset($user->work_schedule_id)) {
            $workSchedule = WorkSchedule::find($user->work_schedule_id);
            if(isset($workSchedule->schedule_break_time)) {
                if ($now->gte(Carbon::parse($workSchedule->schedule_break_time))) {
                    if ($now->lte(Carbon::parse($workSchedule->schedule_back_time))) {
                        $valid = true;
                    }
                }
            }
            if(isset($workSchedule->schedule_end_time)) {
                if ($now->gte(Carbon::parse($workSchedule->schedule_end_time))) {
                    $valid = true;
                }
            }
        }
        return $valid;

    }
}
