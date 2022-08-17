<?php

namespace App\Domain\ACL\Actions;

use App\Domain\ACL\Models\Role;
use App\Domain\Employee\Models\Employee;

class GetHrProRoles
{
    /**
     * getting Custom roles
     *
     * @param  null
     * @return array $data
     */
    public function execute()
    {
        $roles = Role::all();
        foreach ($roles as $role) {
            $data[$role->name] = Employee::role($role->name)->get();
        }
        // getting custom roles
        $data['roles'] = (new GetHrProTypeRoles())->execute();

        return $data;
    }
}
