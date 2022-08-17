<?php

namespace App\Domain\TimeOff\Actions;

class ManageTimeOffTypesAndPoliciesPermissions
{
    /**
     * @param $permissionType
     * @param $id
     * @param $action
     */
    public function execute($permissionType, $id, $action)
    {
        if ($permissionType == "timeofftype") {
            $canRequestTimeOff = 'request ' . $permissionType . ' ' . $id;
            $createOrDeletePermission = $action . "Permission";
            $createOrDelete = new CreateOrDeletePermission();
            $createOrDelete->execute($createOrDeletePermission, $canRequestTimeOff);
        }
        $permissionActions = ['view', 'edit'];
        foreach ($permissionActions as $permissionAction) {
            $permission = $permissionAction . ' ' . $permissionType . ' ' . $id;
            $createOrDeletePermission = $action . "Permission";
            $createOrDelete = new CreateOrDeletePermission();
            $createOrDelete->execute($createOrDeletePermission, $permission);
        }
    }
}
