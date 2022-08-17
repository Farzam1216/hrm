<?php


namespace App\Domain\Employee\Actions;

use App\Domain\ACL\Models\Role;

class GetEmployeeTypeRoles
{
    public function execute()
    {
        $employeeRoles = Role::where('type', 'employee')->orderBy('id', 'asc')->get();
        $employeeRoles->shift(); //Leave first role of employee type, it is default role and not assignable to any user
        $employeeRoles->all();
        return $employeeRoles;
    }
}
