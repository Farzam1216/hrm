<?php


namespace App\Domain\Task\Actions;

use App\Domain\Employee\Models\Employee;
use App\Domain\Task\Models\EmployeeTask;

class MyTask
{
    /**
     * @param $data
     * @return array
     */
    public function execute($employeeID, $data)
    {
        $employee = Employee::find($employeeID);
        $employeesTaskFor = EmployeeTask::with('Task', 'assignedfor')->where('assigned_to', $employeeID)->where('task_completion_status', 0)->get();
        foreach ($employeesTaskFor as $employeeTaskFor) {
            $date = $employeeTaskFor->assignedfor->joining_date;
            if ($employeeTaskFor->status == 'none') {
                $employeeTaskFor->status_value = 'none';
            } elseif ($employeeTaskFor->status == 'on_hire_date') {
                $employeeTaskFor->status_value = $date;
            } elseif ($employeeTaskFor->status == 'after') {
                if (stripos($employeeTaskFor->status_value, 'Days') !== false) {
                    $employeeTaskFor->status_value = date('Y-m-d', strtotime($date . $employeeTaskFor->status_value));
                }
                if (stripos($employeeTaskFor->status_value, 'Weeks') !== false) {
                    $employeeTaskFor->status_value = date('Y-m-d', strtotime($date . $employeeTaskFor->status_value));
                }
                if (stripos($employeeTaskFor->status_value, 'Months') !== false) {
                    $employeeTaskFor->status_value = date('Y-m-d', strtotime($date . $employeeTaskFor->status_value));
                }
            } elseif ($employeeTaskFor->status == 'before') {
                if (stripos($employeeTaskFor->status_value, 'Days') !== false) {
                    $employeeTaskFor->status_value = date('Y-m-d', strtotime($date . '-' . $employeeTaskFor->status_value));
                }
                if (stripos($employeeTaskFor->status_value, 'Weeks') !== false) {
                    $employeeTaskFor->status_value = date('Y-m-d', strtotime($date . '-' . $employeeTaskFor->status_value));
                }
                if (stripos($employeeTaskFor->status_value, 'Months') !== false) {
                    $employeeTaskFor->status_value = date('Y-m-d', strtotime($date . '-' . $employeeTaskFor->status_value));
                }
            }
        }
        $completedStatusTasks = EmployeeTask::with('Task', 'assignedTo', 'assignedFor')->where('assigned_to', $employeeID)->where('task_completion_status', 1)->get();
        foreach ($completedStatusTasks as $completedStatusTask) {
            $date = $completedStatusTask->assignedfor->joining_date;
            if ($completedStatusTask->status == 'none') {
                $completedStatusTask->status_value = 'none';
            } elseif ($completedStatusTask->status == 'on_hire_date') {
                $completedStatusTask->status_value = $date;
            } elseif ($completedStatusTask->status == 'after') {
                if (stripos($completedStatusTask->status_value, 'Days') !== false) {
                    $completedStatusTask->status_value = date('Y-m-d', strtotime($date . $completedStatusTask->status_value));
                }
                if (stripos($completedStatusTask->status_value, 'Weeks') !== false) {
                    $completedStatusTask->status_value = date('Y-m-d', strtotime($date . $completedStatusTask->status_value));
                }
                if (stripos($completedStatusTask->status_value, 'Months') !== false) {
                    $completedStatusTask->status_value = date('Y-m-d', strtotime($date . $completedStatusTask->status_value));
                }
            } elseif ($completedStatusTask->status == 'before') {
                if (stripos($completedStatusTask->status_value, 'Days') !== false) {
                    $completedStatusTask->status_value = date('Y-m-d', strtotime($date . '-' . $completedStatusTask->status_value));
                }
                if (stripos($completedStatusTask->status_value, 'Weeks') !== false) {
                    $completedStatusTask->status_value = date('Y-m-d', strtotime($date . '-' . $completedStatusTask->status_value));
                }
                if (stripos($completedStatusTask->status_value, 'Months') !== false) {
                    $completedStatusTask->status_value = date('Y-m-d', strtotime($date . '-' . $completedStatusTask->status_value));
                }
            }
        }
        $result = [
            'employeesTaskFor' => $employeesTaskFor,
            'employee' => $employee,
            'completedStatusTasks' => $completedStatusTasks,
        ];
        return $result;
    }
}
