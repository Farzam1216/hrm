<?php


namespace App\Domain\Task\Actions;

use App\Domain\Employee\Models\Employee;
use App\Domain\Task\Models\EmployeeTask;
use Illuminate\Support\Facades\Auth;

class CheckEmployeeDepartments
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

        $tasksAssignedToAllDepartments = [];
        foreach ($assignedForTasks as $assignedTask) {
            $i = 0;
            $employeesThisTaskIsAssignedTo = EmployeeTask::where('task_id', $id)->where('assigned_by', Auth::user()->id)->where('assigned_to', $firstAssignment->assigned_to)->get();

            foreach ($employeesThisTaskIsAssignedTo as $employeeThisTaskIsAssignedTo) {

                // Fetch the department id of the employee who this task is assigned
                $departmentId = Employee::where('id', $employeeThisTaskIsAssignedTo->assigned_for)->pluck('department_id');
                $employeesInDepartment = Employee::where('department_id', $departmentId[0])->get();
                //counter for checking all employees of this department
                $employeeCount = 0;
                //checking employees lie in the same department
                foreach ($employeesInDepartment as $employeeInDepartment) {
                    if (EmployeeTask::where("assigned_for", $employeeInDepartment->id)->count() >= 1) {
                        ++$employeeCount;
                    }
                }
                //if one department is already selected then
                if (!empty($tasksAssignedToAllDepartments[$assignedTask->task_id]) && in_array($departmentId[0], $tasksAssignedToAllDepartments[$assignedTask->task_id])) {
                    continue;
                }
                if ($employeeCount == $employeesInDepartment->count()) {
                    $tasksAssignedToAllDepartments[$assignedTask->task_id][$i] = $departmentId[0];
                }
                $i = $i + 1;
            }
            return $tasksAssignedToAllDepartments;
        }
    }
}
