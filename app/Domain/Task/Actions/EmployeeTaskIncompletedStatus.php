<?php

namespace App\Domain\Task\Actions;

use App\Domain\Task\Models\EmployeeTask;

class EmployeeTaskIncompletedStatus
{
    /**
     * @param $data
     * @return EmployeeTask|EmployeeTask[]|Builder|Builder[]|Collection|Model|null
     */
    public function execute($data)
    {
        $employeTask = EmployeeTask::with('task', 'assignedTo', 'assignedFor')->where('assigned_to', $data['employeeID'])->find($data['taskCompletionStatusId']);
        $employeTask->task_completion_status = 0;
        $employeTask->save();
        $date = $employeTask->assignedfor->joining_date;
        if ($employeTask->status == 'none') {
            $employeTask->status_value = 'none';
        } elseif ($employeTask->status == 'on_hire_date') {
            $employeTask->status_value = $date;
        } elseif ($employeTask->status == 'after') {
            if (stripos($employeTask->status_value, 'Days') !== false) {
                $employeTask->status_value = date('Y-m-d', strtotime($date . $employeTask->status_value));
            }
            if (stripos($employeTask->status_value, 'Weeks') !== false) {
                $employeTask->status_value = date('Y-m-d', strtotime($date . $employeTask->status_value));
            }
            if (stripos($employeTask->status_value, 'Months') !== false) {
                $employeTask->status_value = date('Y-m-d', strtotime($date . $employeTask->status_value));
            }
        } elseif ($employeTask->status == 'before') {
            if (stripos($employeTask->status_value, 'Days') !== false) {
                $employeTask->status_value = date('Y-m-d', strtotime($date . '-' . $employeTask->status_value));
            }
            if (stripos($employeTask->status_value, 'Weeks') !== false) {
                $employeTask->status_value = date('Y-m-d', strtotime($date . '-' . $employeTask->status_value));
            }
            if (stripos($employeTask->status_value, 'Months') !== false) {
                $employeTask->status_value = date('Y-m-d', strtotime($date . '-' . $employeTask->status_value));
            }
        }
        return $employeTask;
    }
}
