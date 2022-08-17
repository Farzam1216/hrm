<?php

namespace App\Domain\ACL\Actions;

use App\Domain\ACL\Models\Role;

class EditManagersRoleAndPermissions
{
    public function execute($request, $id)
    {
        $subRole = (isset($request->hasEmployeeRole) && $request->hasEmployeeRole == 'yes') ?  $request->employeeRole : null;
        try {
            $role = Role::findById($id);
            $role->name = $request->name;
            $role->description = $request->description;
            $role->sub_role = $subRole;
            $employeePermissionsfilter = (isset($request->employeePermission)) ?  array_filter($request->employeePermission) : null;
            $role->syncPermissions($employeePermissionsfilter);
            $role->update();
        } catch (\Throwable $th) {
            return false;
        }
        if (isset($request->employeePermission)) {
            $employeePermission = array_filter($request->employeePermission);
            $accessLevel['access-level'] = $request->managerRole['access-level'];
            $role->givePermissionTo($accessLevel, $employeePermission);
            return true;
        }
        return false;
    }
}
