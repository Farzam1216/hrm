<?php

namespace App\Domain\ACL\Actions;

use App\Domain\ACL\Models\Role;

class SelectedEmployees
{
    /**
     *  Selected employees in role
     *
     * @param  integer roleID
     * @return Collection employee
     */
    public function execute($roleID)
    {
        $role = Role::find($roleID);
        return $employee = $role->users->each(function ($user) {
            $user->name;
        });
    }
}
