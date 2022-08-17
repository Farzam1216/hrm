<?php

namespace App\Domain\ACL\Actions;

use App\Domain\ACL\Models\Role;

class GetCustomTypeRoles
{
    /**
     * Function will return manager type role
     *
     * @return Collection
     */
    public function execute()
    {
        return Role::where('type', 'custom')->orderBy('id', 'asc')->get();
    }
}
