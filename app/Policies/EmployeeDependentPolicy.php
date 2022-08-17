<?php

namespace App\Policies;

use App\Domain\Benefit\Actions\GetAuthorizedUserPermissions;
use App\Domain\Employee\Models\Employee;
use App\Traits\AccessibleFields;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeDependentPolicy
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
     * Method for Employee Dependent's Index Page
     * @param Employee $user //Authenticated User
     * @param $employees
     * @return bool
     */
    public function view(Employee $user, $employees)
    {
        $permissions = $this->getAuthorizedUserPermissions->execute($employees);
        if (!empty($permissions['dependents'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Method for Employee Dependent's Create Page
     * @param Employee $user //Authenticated User
     * @param $employee
     * @return bool
     */
    public function create(Employee $user, $employee)
    {
        $permissions = $this->getAuthorizedUserPermissions->execute($employee);
        if (isset($permissions['dependents'][$employee[0]->id]['employeedependent first_name']) && ($permissions['dependents'][$employee[0]->id]['employeedependent first_name'] != "view") && (isset($permissions['dependents'][$employee[0]->id]['employeedependent last_name']) && ($permissions['dependents'][$employee[0]->id]['employeedependent last_name'] != "view"))) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * Method for Employee Dependent's Edit Page
     * @param Employee $user //Authenticated User
     * @param $employees
     * @return bool
     */
    public function edit(Employee $user, $employee)
    {
        $permissions = $this->getAuthorizedUserPermissions->execute($employee);
        if (isset($permissions['dependents'][$employee[0]->id]) && (in_array('edit', $permissions['dependents'][$employee[0]->id]) || in_array('edit_with_approval', $permissions['dependents'][$employee[0]->id]))) {
            return true;
        } else {
            return false;
        }
    }
}
