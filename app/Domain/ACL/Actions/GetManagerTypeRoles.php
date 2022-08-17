<?php

namespace App\Domain\ACL\Actions;

use App\Domain\ACL\Models\Role;

class GetManagerTypeRoles
{
    /**
     * Function will return manager type role
     *
     * @return Collection
     */
    public function execute()
    {
        $managerRole = Role::where('type', 'manager')->orderBy('id', 'asc')->get();
        $managerRole->shift();
        return $managerRole;
    }
}
