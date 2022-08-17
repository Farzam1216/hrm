<?php

namespace App\Domain\ACL\Actions;

use App\Domain\ACL\Models\Role;
use App\Domain\Employee\Models\Employee;

class GetManagerRoles
{
    /**
     * getting Manager roles
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
        // geting manager roles
        $data['roles'] = (new GetManagerTypeRoles())->execute();
        return $data;
    }
}
