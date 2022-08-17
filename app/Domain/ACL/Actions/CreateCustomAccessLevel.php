<?php

namespace App\Domain\ACL\Actions;

class CreateCustomAccessLevel
{
    public function execute()
    {
        $defaultEmployeePermissions = (new GetDefaultPermissions())->execute("employee");
        $defaultManagerPermissions = (new GetDefaultPermissions())->execute("manager");
        $data['defaultPermissions'] = (new AddAdditionalDefaultPermissionsForCustomRole())->execute(
            array_merge($defaultEmployeePermissions, $defaultManagerPermissions)
        );
        $data['manageFullPermissions'] = (new GetFullAccessPermissionsForCustomRole())->execute();
        $data['employeeRoles'] = (new GetEmployeeTypeRoles())->execute();
        $data['employees'] = (new GetEmployees())->execute();
        return $data;
    }
}
