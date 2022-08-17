<?php

namespace App\Domain\ACL\Actions;

use App\Domain\ACL\Models\Role;

class GetHrProTypeRoles
{
    /**
     * Function will return manager type role
     *
     * @return Collection
     */
    public function execute()
    {
        return Role::where('type', 'hr-pro')->orderBy('id', 'asc')->get();
    }
}
