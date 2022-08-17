<?php

namespace App\Domain\ACL\Actions;

use App\Domain\ACL\Models\Role;

class GetRole
{
    /**
     * getting  role
     *
     * @param  integer id
     * @return Collection Role
     */
    public function execute($id)
    {
        return Role::find($id);
    }
}
