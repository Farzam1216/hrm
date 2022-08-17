<?php

namespace App\Domain\ACL\Actions;

use App\Domain\ACL\Models\Role;
use Illuminate\Support\Facades\DB;

class DuplicateCustomRole
{
    public function execute($roleId)
    {
        $role = Role::find($roleId);
        $duplicateRole = (new CreateDuplicateRole())->execute($role, 'custom');
        if ($role != false) {
            $fullPermissions = $role->permissions()->where('access_level_id', (new GetAccessLevelID())->execute('All Employees'))->get();
            $permissions = $role->permissions()->where('access_level_id', '!=', (new GetAccessLevelID())->execute('All Employees'))->get();

            if ($permissions->isNotEmpty() && $permissions->first()->pivot->access_level_id == (new GetAccessLevelID())->execute('Specific Employees')) {
                $specificEmployeesPermissions = DB::table('custom_role_permissions')->where('role_id', $role->id)->get();
                $accessLevel['access-level'] = (int) $permissions->first()->pivot->access_level_id;
                $accessLevel['employees'] = array_column($specificEmployeesPermissions->toArray(), 'employee_id');
                $duplicateRole->givePermissionTo($accessLevel, $permissions);
            } elseif ($permissions->isNotEmpty()) {
                $accessLevel['access-level'] = ($permissions->isNotEmpty()) ? $permissions->first()->pivot->access_level_id : null;
                $duplicateRole->givePermissionTo($accessLevel, $permissions->pluck('name')->toArray());
            }
            //Give Full Permissions
            if ($fullPermissions->isNotEmpty()) {
                $accessLevel['access-level'] = ($fullPermissions->isNotEmpty()) ? $fullPermissions->first()->pivot->access_level_id : null;
                $duplicateRole->givePermissionTo($accessLevel, $fullPermissions->pluck('name')->toArray());
            }
            return $duplicateRole->id;
        }
    }
}
