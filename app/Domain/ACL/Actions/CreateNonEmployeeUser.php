<?php

namespace App\Domain\ACL\Actions;

class CreateNonEmployeeUser
{
    public function execute()
    {
        $data['managerRoles'] = (new GetManagerRoles())->execute();
        $data['customRoles'] = (new GetCustomRoles())->execute();
        return $data;
    }
}
