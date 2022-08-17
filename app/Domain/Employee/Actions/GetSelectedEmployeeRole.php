<?php


namespace App\Domain\Employee\Actions;

class GetSelectedEmployeeRole
{
    public function execute($employee)
    {
        //A user with employee type role can only have one role. Multiple employee type roles are not allowed. (except for the
        //sub-roles)
        $role = $employee->roles()->first();
        $roles = null;
        if (empty($role)) {
            $roles['noAccess'] = true;
            $roles['employeeRoles'] = (new GetEmployeeTypeRoles())->execute();
        } elseif ($role->type == 'employee') {
            $roles['currentRole'] = $role;
            $roles['employeeRoles'] = (new GetEmployeeTypeRoles())->execute();
        }

        return $roles;
    }
}
