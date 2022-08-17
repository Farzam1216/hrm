<?php

namespace App\Domain\ACL\Actions;

class StoreManagerRoleAndPermissions
{
    /**
     * Store manager role with permissions
     * @param Request $request
     * @return bool
     */
    public function execute($request)
    {
        $role = (new StoreRole())->execute($request, 'manager');

        if (isset($request->managerRole['permission']) && $role != false) {
            $managerPermissions = array_filter($request->managerRole['permission']);
            $role->givePermissionTo($request->managerRole, $managerPermissions);
            return true;
        }
        return false;
    }
}
