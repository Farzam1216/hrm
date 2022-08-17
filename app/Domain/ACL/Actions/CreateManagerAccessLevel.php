<?php

namespace App\Domain\ACL\Actions;

class CreateManagerAccessLevel
{
    public function execute()
    {
        $data['employeeRoles'] = (new GetEmployeeTypeRoles())->execute();
        $data['defaultPermissions'] = (new GetDefaultPermissions())->execute("manager");
        return $data;
    }
}
