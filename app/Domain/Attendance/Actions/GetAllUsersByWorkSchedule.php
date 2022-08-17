<?php


namespace App\Domain\Attendance\Actions;


use App\Domain\Attendance\Models\WorkSchedule;
use App\Domain\Employee\Models\Employee;
use Illuminate\Database\Eloquent\Model;

class GetAllUsersByWorkSchedule
{
    /**
     * add available plans in benefitGroup
     * @param $request
     */
    public function execute($id)
    {
        $employees = Employee::where('work_schedule_id',$id)->get();
        $assignedEmployeeIDByWorkSchedule = [];
        foreach ($employees as $employee) {
            $assignedEmployeeIDByWorkSchedule[] = $employee->id;
        }


        return $assignedEmployeeIDByWorkSchedule;
    }
}
