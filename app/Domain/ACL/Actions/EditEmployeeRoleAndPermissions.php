<?php

namespace App\Domain\ACL\Actions;

use App\Domain\ACL\Models\Role;

class EditEmployeeRoleAndPermissions
{
    public function execute($request, $id)
    {
        try {
            $role = Role::findById($id);
            $role->name = $request->name;
            $role->description = $request->description;
            $role->sub_role = $request->employeeRole;
            $employeePermissionsfilter = (isset($request->employeePermission)) ?  array_filter($request->employeePermission) : null;
            $role->syncPermissions($employeePermissionsfilter);
            $role->update();
        } catch (\Throwable $th) {
            return false;
        }
        if (isset($request->employeePermission) && $role != false) {
            $employeePermission = array_filter($request->employeePermission);
            $accessLevel['access-level'] = 0;
            $role->givePermissionTo($accessLevel, $employeePermission);
            return true;
        }
        return false;
    }
}
