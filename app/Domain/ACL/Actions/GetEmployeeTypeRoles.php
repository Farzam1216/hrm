<?php

namespace App\Domain\ACL\Actions;

use App\Domain\ACL\Models\Role;

class GetEmployeeTypeRoles
{
    /**
     * Return all roles where type is employee
     * @return Collection $employeeRoles
     */
    public function execute()
    {
        $employeeRoles = Role::where('type', 'employee')->orderBy('id', 'asc')->get();
        $employeeRoles->shift(); //Leave first role of employee type, it is default role and not assignable to any user
        $employeeRoles->all();
        return  $employeeRoles;
    }
}
