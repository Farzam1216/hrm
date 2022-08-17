<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitGroup;
use App\Domain\Benefit\Models\BenefitPlan;
use App\Domain\Benefit\Models\EmployeeBenefit;
use App\Domain\Benefit\Models\EmployeeDependent;

class AuthorizeUser
{
    /**
     * @param $policyMethod
     * @param $controllerName
     * @param $modelName
     * @param $employees
     * @return mixed
     */
    public function execute($policyMethod, $controllerName, $modelName, $employees, $benefitPlan_ID = null)
    {
        $models = [
            'employeeBenefits' => EmployeeBenefit::class,
            'benefitGroup' => BenefitGroup::class,
            'dependents' => EmployeeDependent::class,
            'benefitPlans' => BenefitPlan::class
        ];
        return $controllerName->authorize($policyMethod, [$models[$modelName], $employees, $benefitPlan_ID]);
    }
}
