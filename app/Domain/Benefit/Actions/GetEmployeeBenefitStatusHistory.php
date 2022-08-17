<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\EmployeeBenefit;
use App\Domain\Benefit\Models\EmployeeBenefitStatus;
use Illuminate\Database\Eloquent\Builder;

class GetEmployeeBenefitStatusHistory
{
    /**
     * @param $userId
     * @return EmployeeBenefitStatus[]|Builder[]|Collection
     */
    public function execute($userId)
    {
        //return employee benefit history
        $employeeBenefitStatusHistory = collect();
        $benefitStatuses = EmployeeBenefit::with('employeeBenefitStatuses', 'employeeBenefitStatusHistories')->where('employee_id', $userId)->get();
        foreach ($benefitStatuses as $status) {
            $employeeBenefitStatusHistory = $employeeBenefitStatusHistory->merge($status->employeeBenefitStatuses);
            $employeeBenefitStatusHistory = $employeeBenefitStatusHistory->merge($status->employeeBenefitStatusHistories);
        }
        return  $employeeBenefitStatusHistory;
    }
}
