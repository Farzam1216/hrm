<?php


namespace App\Domain\Task\Actions;

use App\Domain\Employee\Models\Employee;
use App\Domain\Task\Models\EmployeeTask;
use Illuminate\Support\Facades\Auth;

class CheckEmployeeLocations
{
    /**
     * @param $id
     * @return array
     */
    public function execute($id)
    {
        $firstAssignment = EmployeeTask::where('task_id', $id)->where('assigned_by', Auth::user()->id)->first();
        //get all employees of this task
        $assignedForTasks = EmployeeTask::where('task_id', $id)->where('assigned_by', Auth::user()->id)->where('assigned_to', $firstAssignment->assigned_to)->get();

        $tasksAssignedToAllLocations = [];
        foreach ($assignedForTasks as $assignedTask) {
            $i = 0;
            $employeesThisTaskIsAssignedTo = EmployeeTask::where('task_id', $id)->where('assigned_by', Auth::user()->id)->where('assigned_to', $firstAssignment->assigned_to)->get();

            foreach ($employeesThisTaskIsAssignedTo as $employeeThisTaskIsAssignedTo) {

                // Fetch the location id of the employee who this task is assigned
                $locationId = Employee::where('id', $employeeThisTaskIsAssignedTo->assigned_for)->pluck('location_id');
                $employeesInLocation = Employee::where('location_id', $locationId[0])->get();
                //counter for checking all employees of this location
                $employeeCount = 0;
                //checking employees lie in the same location
                foreach ($employeesInLocation as $employeeInLocation) {
                    if (EmployeeTask::where("assigned_for", $employeeInLocation->id)->count() >= 1) {
                        ++$employeeCount;
                    }
                }
                //if one location is already selected then
                if (!empty($tasksAssignedToAllLocations[$assignedTask->task_id]) && in_array($locationId[0], $tasksAssignedToAllLocations[$assignedTask->task_id])) {
                    continue;
                }
                if ($employeeCount == $employeesInLocation->count()) {
                    $tasksAssignedToAllLocations[$assignedTask->task_id][$i] = $locationId[0];
                }
                $i = $i + 1;
            }

            return $tasksAssignedToAllLocations;
        }
    }
}
