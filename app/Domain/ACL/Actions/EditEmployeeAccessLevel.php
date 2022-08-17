<?php

namespace App\Domain\ACL\Actions;

use App\Domain\ACL\Models\Role;

class EditEmployeeAccessLevel
{
    public function execute($id)
    {
        $data['role'] = Role::findById($id);
        $defaultPermissions = (new GetDefaultPermissions())->execute("employee");
        $selectedPermission = (new GetPermissions())->execute($id);
        $data['defaultPermissions'] = (new GetUniquePermissions)->execute($defaultPermissions, $selectedPermission);
        $data['canRequest'] = isset($data['defaultPermissions']['time_off']['can_request_time_off_checked']);
        if (isset($data['defaultPermissions']['time_off']['can_request_time_off_checked'])) {
            unset($data['defaultPermissions']['time_off']['can_request_time_off_checked']);
        }
        return $data;
    }
}
