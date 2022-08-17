<?php

namespace App\Domain\ACL\Actions;

use App\Domain\ACL\Models\Role;
use App\Domain\Employee\Models\Employee;

class GetEmployeeRoles
{
    /**
     * getting Employee roles
     *
     * @param  null
     * @return array $data
     */
    public function execute()
    {
        // getting all roles
        $allEmployees = Employee::all();
        $data = [
            'allEmployees' => $allEmployees
        ];
        $roles = Role::all();
        foreach ($roles as $role) {
            $data[$role->name] = Employee::role($role->name)->get();
        }
        //        $getting employeeRoles
        $data['roles'] = (new GetEmployeeTypeRoles())->execute();
        return $data;
    }
}
