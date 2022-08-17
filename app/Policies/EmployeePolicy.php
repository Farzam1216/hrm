<?php

namespace App\Policies;

use App\Domain\Employee\Actions\GetAuthorizedUserPermissions;
use App\Domain\Employee\Actions\PermissionsToAccessEmployeeInformation;
use App\Domain\Employee\Models\Employee;
use App\Traits\AccessibleFields;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeePolicy
{
    use AccessibleFields;
    use HandlesAuthorization;

    private $getAuthorizedUserPermissions;
    private $permissionsToAccessEmployeeInformation;
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
        $this->permissionsToAccessEmployeeInformation = new PermissionsToAccessEmployeeInformation();
    }

    /**
     * @param Employee $user
     * @param $employees
     */

    public function view(Employee $user, $employees)
    {
    }

    /**
     * @param Employee $user
     * @param $employees
     * @return bool
     */

    public function edit(Employee $user, $employees)
    {
        $permissions=$this->getAuthorizedUserPermissions->execute($employees);
        $basicPermissions =$this->permissionsToAccessEmployeeInformation->execute($permissions);
        if ((!empty($permissions['employee']) && isset($basicPermissions['employee']) && array_search('true', $basicPermissions['employee'])) || !empty($permissions['department']) || !empty($permissions['location'])
            || !empty($permissions['educationType']) || !empty($permissions['education']) ||  !empty($permissions['secondaryLanguage'])
            || !empty($permissions['assets']) || !empty($permissions['visaType'])
            || !empty($permissions['employeeVisa']) || !empty($permissions['employeeAccessLevel']) || !empty($permissions['employeeDocument'])
            || !empty($permissions['benefits']) || !empty($permissions['benefitGroup']) || !empty($permissions['dependents'])
            || !empty($permissions['benefitPlans']) || (!empty($permissions['timeofftype']) && isset($basicPermissions['timeofftype']) && array_search('true', $basicPermissions['timeofftype']))
            || !empty($permissions['policy']) || !empty($permissions['tasks'])) {
            return true;
        } else {
            return false;
        }
    }
}
