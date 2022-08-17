<?php

namespace App\Domain\ACL\Actions;

class EditManagerAccessLevel
{
    public function execute($id)
    {
        $data['roleAndAccessLevel'] = (new GetRoleAndAccessLevel())->execute($id);
        $defaultPermissions         = (new GetDefaultPermissions())->execute("manager");
        $selectedPermission         = (new GetPermissions())->execute($id);
        $data['defaultPermissions'] = (new GetUniquePermissions())->execute($defaultPermissions, $selectedPermission);
        $data['employeeRoles']      = (new GetEmployeeTypeRoles())->execute();
        $data['subRoleAccessLevel'] = (new GetSubRoleAccessLevel())->execute($id);
        return $data;
    }
}
