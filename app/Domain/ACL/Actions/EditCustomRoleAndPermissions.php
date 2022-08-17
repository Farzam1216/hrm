<?php

namespace App\Domain\ACL\Actions;

use App\Domain\ACL\Models\Role;
use Illuminate\Support\Facades\DB;

class EditCustomRoleAndPermissions
{
    /**
     * Store Custom level with permission.
     * @param $request
     * @return bool
     */
    public function execute($request, $id)
    {
        $subRole = (isset($request->hasEmployeeRole) && $request->hasEmployeeRole == 'yes' && $request->employeeRole != 'full') ?  $request->employeeRole : null;
        try {
            $role = Role::findById($id);
            $role->name = $request->name;
            $role->description = $request->description;
            $role->sub_role = $subRole;
            DB::table('role_permission_has_access_levels')->where('role_id', $role->id)->delete();
            DB::table('custom_role_permissions')->where('role_id', $role->id)->delete();
            $customPermissionsfilter = (isset($request->customPermissions)) ? array_filter($request->customPermissions) : [];
            $fullPermissionsfilter = (isset($request->fullPermissions)) ? array_filter($request->fullPermissions) : [];
            $role->syncPermissions($customPermissionsfilter);
            $role->syncPermissions($fullPermissionsfilter);
            $role->update();
        } catch (\Throwable $th) {
            return false;
        }
        $customPermissions = array_filter($request->customPermissions);
        if (isset($customPermissions)) {
            (new GivePermissionsToCustomRole())->execute($request, $customPermissions, $role);
        }

        if (isset($request->fullPermissions)) {
            $accessLevel['access-level'] = 4;
            $role->givePermissionTo($accessLevel, $request->fullPermissions);
        }
        return true;
    }
}
