<?php


namespace App\Domain\Task\Actions;

use App\Domain\Employee\Models\Employee;
use App\Domain\Task\Models\EmployeeTask;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UpdateEmployeeTaskAssign
{
    /**
     * @param $data
     * @param $id
     */
    public function execute($data, $id)
    {
        $updateEmployeeTask = EmployeeTask::where('task_id', $id)->where('assigned_by', Auth::user()->id)->where('assigned_to', $data['assignedTo'])->get();
        foreach ($updateEmployeeTask as $EmployeeTask) {
            $EmployeeTask->delete();
        }
        if (isset($data['department'])) {
            foreach ($data['department'] as $key => $department) {
                if ($department == 'on') {
                    $employeesDepartment = Employee::where('department_id', $key)->get();
                    foreach ($employeesDepartment as $employeeDepartment) {
                        $employeeTask = new EmployeeTask();
                        $employeeTask->task_id = $id;
                        if ($employeeDepartment->id != $data['assignedTo']) {
                            $employeeTask->assigned_for = $employeeDepartment->id;
                            $employeeTask->assigned_by = Auth::user()->id;
                            if ($data['dueDate']['status'] == 'none') {
                                $employeeTask->status = $data['dueDate']['status'];
                            } elseif ($data['dueDate']['status'] == 'on_hire_date') {
                                $employeeTask->status = $data['dueDate']['status'];
                            } elseif ($data['dueDate']['status'] == 'specific_date') {
                                $status_value = $data['dueDate']['days'] . $data['dueDate']['duration'];
                                $employeeTask->status = $data['dueDate']['status_value'];
                                $employeeTask->status_value = $status_value;
                            }
                            $employeeTask->assigned_to = $data['assignedTo'];
                            $employeeTask->save();
                        }
                    }
                }
            }
        }
        if (isset($data['location'])) {
            foreach ($data['location'] as $key => $location) {
                if ($location == 'on') {
                    $employeesLocation = Employee::where('location_id', $key)->get();
                    foreach ($employeesLocation as $employeeLocation) {
                        $employeeTask = new EmployeeTask();
                        $employeeTask->task_id = $id;
                        $employeeTask->assigned_for = $employeeLocation->id;
                        $employeeTask->assigned_by = Auth::user()->id;
                        if ($data['dueDate']['status'] == 'none') {
                            $employeeTask->status = $data['dueDate']['status'];
                        } elseif ($data['dueDate']['status'] == 'on_hire_date') {
                            $employeeTask->status = $data['dueDate']['status'];
                        } elseif ($data['dueDate']['status'] == 'specific_date') {
                            $status_value = $data['dueDate']['days'] . $data['dueDate']['duration'];
                            $employeeTask->status = $data['dueDate']['status_value'];
                            $employeeTask->status_value = $status_value;
                        }
                        $employeeTask->assigned_to = $data['assignedTo'];
                        $employeeTask->save();
                    }
                }
            }
        }
        if (isset($data['employmentStatus'])) {
            foreach ($data['employmentStatus'] as $key => $employmentStatus) {
                if ($employmentStatus == 'on') {
                    $employeesEmploymentStatus = DB::table('employees')->where('employment_status_id', $key)->get();
                    foreach ($employeesEmploymentStatus as $employeeEmploymentStatus) {
                        $employeeTask = new EmployeeTask();
                        $employeeTask->task_id = $id;
                        $employeeTask->assigned_for = $employeeEmploymentStatus->id;
                        $employeeTask->assigned_by = Auth::user()->id;
                        if ($data['dueDate']['status'] == 'none') {
                            $employeeTask->status = $data['dueDate']['status'];
                        } elseif ($data['dueDate']['status'] == 'on_hire_date') {
                            $employeeTask->status = $data['dueDate']['status'];
                        } elseif ($data['dueDate']['status'] == 'specific_date') {
                            $status_value = $data['dueDate']['days'] . $data['dueDate']['duration'];
                            $employeeTask->status = $data['dueDate']['status_value'];
                            $employeeTask->status_value = $status_value;
                        }
                        $employeeTask->assigned_to = $data['assignedTo'];
                        $employeeTask->save();
                    }
                }
            }
        }
    }
}
