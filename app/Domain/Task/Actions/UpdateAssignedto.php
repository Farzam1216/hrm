<?php


namespace App\Domain\Task\Actions;

use App\Domain\Task\Models\EmployeeTask;
use Illuminate\Support\Facades\Auth;

class UpdateAssignedTo
{
    /**
     * @param $data
     * @param $id
     */
    public function execute($data, $id)
    {
        $updateAssignedTo = EmployeeTask::where('task_id', $id)->where('assigned_by', Auth::user()->id)->get();
        foreach ($updateAssignedTo as $AssignedTo) {
            if ($AssignedTo->assigned_for != $data['assignedTo']) {
                $AssignedTo->assigned_to = $data['assignedTo'];
                $AssignedTo->save();
            } else {
                $AssignedTo->delete();
            }
        }
    }
}
