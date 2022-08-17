<?php

namespace App\Domain\ACL\Actions;

class StoreEmployeeRoleAndPermissions
{
    /**
     * Store employee role with permissions
     * @param Request $request
     * @return bool
     */
    public function execute($request)
    {
        $role = (new StoreRole)->execute($request, 'employee');
        if (isset($request->employeePermission) && $role != false) {
            $employeePermission = array_filter($request->employeePermission);
            $accessLevel['access-level'] = 0;
            $role->givePermissionTo($accessLevel, $employeePermission);
            return true;
        }
        return false;
    }
}
