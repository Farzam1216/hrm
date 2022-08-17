<?php

namespace App\Domain\ACL\Actions;

use App\Domain\ACL\Models\Role;

class DuplicateManagerRole
{
    public function execute($roleId)
    {
        $role = Role::find($roleId);
        if ($role != false) {
            //Create duplicate role
            $duplicateRole = (new createDuplicateRole())->execute($role, 'manager');
            $permissions = $role->permissions()->get();
            $employeePermission = $permissions->pluck('name')->toArray();
            $accessLevel['access-level'] = ($permissions->isNotEmpty()) ? $permissions->first()->pivot->access_level_id : null;
            $duplicateRole->givePermissionTo($accessLevel, $employeePermission);
            return $duplicateRole->id;
        }
        return false;
    }
}
