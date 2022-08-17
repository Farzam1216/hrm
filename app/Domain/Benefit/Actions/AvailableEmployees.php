<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Employee\Models\Employee;
use Illuminate\Database\Eloquent\Builder;

class AvailableEmployees
{
    /**
     * get available Employees to add in benefitGroup
     * @param $id
     * @return Employee[]|Builder[]|Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function execute($id)
    {
        $employeeInGroup = Employee::whereHas('employeeInBenefitGroup', function ($query) use ($id) {
            $query->where('benefit_group_id', $id);
        })->get();
        $employeeInGroupId = $employeeInGroup->pluck('id');
        $availableEmployees = Employee::with('employeeInBenefitGroup')->whereNotIn('id', $employeeInGroupId)->get();
        return $availableEmployees;
    }
}
