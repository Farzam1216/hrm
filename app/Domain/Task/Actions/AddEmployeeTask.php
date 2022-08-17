<?php
namespace App\Domain\Task\Actions;

use App\Domain\Employee\Models\Employee;
use App\Domain\Task\Models\EmployeeTask;
use Illuminate\Support\Facades\Auth;

class AddEmployeeTask
{
    public function execute($data, $id)
    {

        if (isset($data['department'])) {
            foreach ($data['department'] as $key => $department) {
                if ($department == 'on') {
                    $employeesDepartment = Employee::where('department_id', $key)->get();
                    foreach ($employeesDepartment as $employeeDepartment) {
                        $employeeTask = new EmployeeTask();
                        $employeeTask->task_id = $id;
                        if ($employeeDepartment->id != intVal($data['assignedTo'])) {
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
                        if ($employeeLocation->id != intVal($data['assignedTo'])) {
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
        if (isset($data['employmentStatus'])) {
            foreach ($data['employmentStatus'] as $key => $employmentStatus) {
                if ($employmentStatus == 'on') {
                    $employeesEmploymentStatus = Employee::where('employment_status_id', $key)->get();
                    foreach ($employeesEmploymentStatus as $employeeEmploymentStatus) {
                        $employeeTask = new EmployeeTask();
                        $employeeTask->task_id = $id;
                        $employeeTask->assigned_for = $employeeEmploymentStatus->id;
                        if ($employeeEmploymentStatus->id != intVal($data['assignedTo'])) {
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
}
