<?php


namespace App\Domain\Attendance\Actions;


use App\Domain\Attendance\Models\WorkSchedule;
use App\Domain\Employee\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class GetEmployeeWorkSchedule
{
    /**
     * add available plans in benefitGroup
     * @param $request
     */
    public function execute($id)
    {
        $user = Employee::find($id);
        if(isset($user->work_schedule_id)){
            $workSchedule = WorkSchedule::find($user->work_schedule_id);
            if(isset($workSchedule->schedule_start_time))
            {
                $data['schedule_start_time'] = Carbon::parse($workSchedule->schedule_start_time)->format('H:i');
            }
            else{
                $data['schedule_start_time'] = null;
            }
            if(isset($workSchedule->schedule_end_time))
            {
                $data['schedule_end_time'] = Carbon::parse($workSchedule->schedule_end_time)->format('H:i');
            }
            else{
                $data['schedule_end_time'] = null;
            }
        }else{
            $data['schedule_start_time'] = null;
            $data['schedule_end_time'] = null;
        }


        return $data;
    }
}
