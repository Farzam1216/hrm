<?php

namespace App\Domain\ACL\Actions;

use App\Domain\ACL\Models\Permission;
use Illuminate\Support\Facades\DB;

class GivePermissionsToCustomRole
{
    /**
     * Give permissions to custom role
     * @param Request $request
     * @param array $customPermissions
     * @param instanceof $role
     * @return void
     */
    public function execute($request, $customPermissions, $role)
    {
        if (isset($request->specific_employees) && $request->access_level == 3) {
            foreach ($customPermissions as $permissions) {
                $accessLevel['access-level'] = (int) $request->access_level;
                $accessLevel['employees'] = $request->specific_employees;
                $role->givePermissionTo($accessLevel, $permissions);
            }
        } else {
            foreach ($customPermissions as $permissions) {
                $accessLevel['access-level'] = (int) $request->access_level;
                $role->givePermissionTo($accessLevel, $permissions);
            }
        }
        if ($request->employeeRole == 'full') {
            foreach ($customPermissions as $permissions) {
                $permission = Permission::where('name', $permissions)->first();
                DB::table('role_permission_has_access_levels')
                    ->insert([
                        'role_id' => $role->id,
                        'permission_id' => $permission->id,
                        'access_level_id' => 0
                    ]);
            }
        }
    }
}
