<?php

namespace App\Policies;

use App\Domain\Benefit\Actions\GetAuthorizedUserPermissions;
use App\Domain\Employee\Models\Employee;
use App\Traits\AccessibleFields;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeBenefitPolicy
{
    use HandlesAuthorization;
    use AccessibleFields;

    private $getAuthorizedUserPermissions;


    public function before($user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
    }
    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->getAuthorizedUserPermissions = new GetAuthorizedUserPermissions();
    }

    /**
     * @param Employee $user //Authenticated User
     * @param $employees
     * @return bool
     */
    public function view(Employee $user, $employees)
    {
        $permissions = $this->getAuthorizedUserPermissions->execute($employees);
        if (
            !empty($permissions['benefits']) || !empty($permissions['benefitGroup'])
            || !empty($permissions['dependents']) || !empty($permissions['benefitPlans'])
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param Employee $user
     * @param $employees
     * @return bool
     */
    public function create(Employee $user, $employee, $benefitPlanId)
    {
        $permissions = $this->getAuthorizedUserPermissions->execute($employee);
        if (
            isset($permissions['benefitPlans'][$employee[0]->id]['benefitplan ' . $benefitPlanId])
            && $permissions['benefitPlans'][$employee[0]->id]['benefitplan ' . $benefitPlanId] != "view"
        ) {
            return true;
        } else {
            return false;
        }
    }
}
