<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\Employee\Models\Employee;
use App\Domain\TimeOff\Models\Policy;

class GetEmployeesByPolicy
{
    /**
     * @param $id
     * @return mixed
     */
    public function execute($id)
    {
        $policy = Policy::find($id);
        $assignedEmployees = Employee::whereHas('timeofftypes', function ($query) use ($id) {
            $query->where('attached_policy_id', $id);
        })->get();
        $assignedIds = $assignedEmployees->pluck('id');
        $availableEmployees = Employee::where('status', '!=', '0')->with('timeofftypes')->whereNotIn(
            'id',
            $assignedIds
        )->get();
        return $data = ['availableEmployees' => $availableEmployees, 'policy' => $policy, 'assignedEmployees' => $assignedEmployees];
    }
}
