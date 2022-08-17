<?php


namespace App\Domain\Attendance\Actions;


use App\Domain\Attendance\Models\WorkSchedule;
use App\Domain\Employee\Models\Employee;
use Illuminate\Database\Eloquent\Model;

class AssignWorkScheduleToEmployees
{
    /**
     * add available plans in benefitGroup
     * @param $request
     */
    public function execute($request,$id)
    {
        $employeesWithAssignedWorkSchedule = Employee::where('work_schedule_id',$id)->get();
        foreach ($employeesWithAssignedWorkSchedule as $employee){
            $employee->work_schedule_id=null;
            $employee->save();
        }

        $employeeIDs = explode(",",$request->selected_employees);
        foreach ($employeeIDs as $employeeID){
            $employee = Employee::find($employeeID);
            $employee->work_schedule_id=$id;
            $employee->save();
        }

    }
}
