<?php


namespace App\Domain\Employee\Actions;

use App\Domain\ACL\Models\Role;

class AssignRoleToNewEmployee
{
    public function execute($employeeRole, $employee)
    {
        $role = Role::where('id', $employeeRole)->first();
        $employee->assignRole($role->name);
    }
}
