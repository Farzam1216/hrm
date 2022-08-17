<?php

namespace App\Domain\ACL\Actions;

use App\Domain\ACL\Models\Role;
use Illuminate\Support\Facades\DB;

class DuplicateEmployeeRole
{
    public function execute($roleId)
    {
        $role = Role::find($roleId);
        $permissions = $role->permissions()->get();
        $permissions = $permissions->toArray();
        if ($role != false) {
            $duplicateRole = (new createDuplicateRole())->execute($role, 'employee');
            $employeePermission = array_column($permissions, 'name');
            $accessLevel['access-level'] = DB::table('access_levels')->where('name', 'self')->pluck('id')->first();
            $duplicateRole->givePermissionTo($accessLevel, $employeePermission);
            return $duplicateRole->id;
        }
        return false;
    }
}
