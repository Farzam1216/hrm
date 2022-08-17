<?php

namespace App\Domain\Benefit\Actions;

class ManageBenefitPlanPermissions
{
    /**
     * manage(create/delete) permissions for benefit plans at runtime
     * @param $permissionType
     * @param $id
     */
    public function execute($permissionType, $id, $action)
    {
        $permissionActions = ['view', 'edit'];
        foreach ($permissionActions as $permissionAction) {
            $permission = $permissionAction . ' ' . $permissionType . ' ' . $id;
            $createOrDeletePermission = $action . "Permission";
            if ($createOrDeletePermission == "createPermission") {
                (new CreatePermission())->execute($permission);
            } elseif ($createOrDeletePermission == "deletePermission") {
                (new DeletePermission())->execute($permission);
            }
        }
    }
}
