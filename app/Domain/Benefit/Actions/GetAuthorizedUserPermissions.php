<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\BenefitGroup;
use App\Domain\Benefit\Models\BenefitPlan;
use App\Domain\Benefit\Models\EmployeeBenefit;
use App\Domain\Benefit\Models\EmployeeDependent;
use App\Traits\AccessibleFields;

class GetAuthorizedUserPermissions
{
    use AccessibleFields;
    /**
     * @param $employees
     * @return array
     */
    public function execute($employees)
    {
        $permissions = [];
        $permissions['benefits'] = $this->getAccessibleFieldList(EmployeeBenefit::class, $employees);
        $permissions['benefitGroup'] = $this->getAccessibleFieldList(BenefitGroup::class, $employees);
        $permissions['dependents'] = $this->getAccessibleFieldList(EmployeeDependent::class, $employees);
        $permissions['benefitPlans'] = $this->getAccessibleFieldList(BenefitPlan::class, $employees);
        return $permissions;
    }
}
