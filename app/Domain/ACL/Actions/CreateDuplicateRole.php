<?php

namespace App\Domain\ACL\Actions;

use App\Domain\ACL\Models\Role;

class CreateDuplicateRole
{
    /**
     * Adding employees to role
     *
     * @param  Role $role
     * @param  string $type
     * @return Role
     */
    public function execute($role, $type): Role
    {
        try {
            $duplicateRole = new Role();
            $duplicateRole->name = $role->name . ' copy';
            $duplicateRole->description = $role->description;
            $duplicateRole->type = $type;
            $duplicateRole->sub_role = $role->sub_role;
            $duplicateRole->save();
            return $duplicateRole;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
