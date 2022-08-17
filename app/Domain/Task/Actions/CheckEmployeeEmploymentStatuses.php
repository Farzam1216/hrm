<?php


namespace App\Domain\Task\Actions;

use App\Domain\Employee\Models\Employee;
use App\Domain\Task\Models\EmployeeTask;
use Illuminate\Support\Facades\Auth;

class CheckEmployeeEmploymentStatuses
{
    /**
     * @param $id
     * @return array
     */
    public function execute($id)
    {
        $firstAssignment = EmployeeTask::where('task_id', $id)->where('assigned_by', Auth::user()->id)->first();
        //get all employees for this task
        $assignedForTasks = EmployeeTask::where('task_id', $id)->where('assigned_by', Auth::user()->id)->where('assigned_to', $firstAssignment->assigned_to)->get();
        $tasksAssignedToAllEmploymentStatus = [];
        foreach ($assignedForTasks as $assignedTask) {
            $i = 0;
            $employeesThisTaskIsAssignedTo = EmployeeTask::where('task_id', $id)->where('assigned_by', Auth::user()->id)->where('assigned_to', $firstAssignment->assigned_to)->get();

            foreach ($employeesThisTaskIsAssignedTo as $employeeThisTaskIsAssignedTo) {

                // Fetch the employment status id of the employee who this task is assigned
                $employmentStatusId = Employee::where('id', $employeeThisTaskIsAssignedTo->assigned_for)->pluck('employment_status_id');
                $employeesInEmploymentStatus = Employee::where('employment_status_id', $employmentStatusId[0])->get();
                //counter for checking all employees of this employment status
                $employeeCount = 0;
                //checking employees lie in the same employment status
                foreach ($employeesInEmploymentStatus as $employeeInEmploymentStatus) {
                    if (EmployeeTask::where("assigned_for", $employeeInEmploymentStatus->id)->count() >= 1) {
                        ++$employeeCount;
                    }
                }
                //if one employment status is already selected then
                if (!empty($tasksAssignedToAllEmploymentStatus[$assignedTask->task_id]) && in_array($employmentStatusId[0], $tasksAssignedToAllEmploymentStatus[$assignedTask->task_id])) {
                    continue;
                }
                if ($employeeCount == $employeesInEmploymentStatus->count()) {
                    $tasksAssignedToAllEmploymentStatus[$assignedTask->task_id][$i] = $employmentStatusId[0];
                }
                $i = $i + 1;
            }

            return $tasksAssignedToAllEmploymentStatus;
        }
    }
}
