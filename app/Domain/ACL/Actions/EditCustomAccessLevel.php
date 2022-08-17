<?php

namespace App\Domain\ACL\Actions;

class EditCustomAccessLevel
{
    public function execute($id)
    {
        $data['roleAndAccessLevel'] = (new GetRoleAndAccessLevel())->execute($id);
        $defaultManagerPermissions = (new GetDefaultPermissions())->execute("Manager");
        $defaultEmployeePermissions = (new GetDefaultPermissions())->execute("Employee");
        $defaultPermissions = (new AddAdditionalDefaultPermissionsForCustomRole())->execute(array_merge($defaultEmployeePermissions, $defaultManagerPermissions));
        $selectedPermission = (new GetPermissions())->execute($id);
        $defaultPermissions = (new GetUniquePermissions())->execute($defaultPermissions, $selectedPermission);
        $data['employeeRoles'] = (new GetEmployeeTypeRoles())->execute();
        $data['employees'] = (new GetEmployees())->execute();
        $data['specificEmployees'] = (new GetSpecificEmployeesInCustomRole())->execute($id);
        $data['subRoleAccessLevel'] = (new GetSubRoleAccessLevel())->execute($id);
        //fullAcessPermissions
        $fullAccessPermissions = (new GetFullAccessPermissionsForCustomRole())->execute();
        $selectedFullAccessPermissions = (new GetSelectedFullAcessPermissionForCustomRole())->execute($id);
        $defaultfullAcessPermissions = array_replace_recursive($selectedFullAccessPermissions, $fullAccessPermissions);
        $data['defaultfullAcessPermissions'] = (new RemoveDuplicatePermissions())->execute($defaultfullAcessPermissions);
        $data['defaultPermissions'] = (new SortPermissions())->execute($defaultPermissions);
        return $data;
    }
}
