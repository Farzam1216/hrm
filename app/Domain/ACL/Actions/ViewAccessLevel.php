<?php

namespace App\Domain\ACL\Actions;

class ViewAccessLevel
{
    public function execute()
    {
        $data['accessLevelEmployees'] = (new GetEmployeeRoles())->execute();
        $data['accessLevelManager'] = (new GetManagerRoles())->execute();
        $data['accessLevelCustom'] = (new GetCustomRoles())->execute();
        $data['accessLevelHrPro'] = (new GetHrProRoles())->execute();
        $data['noAccessEmployees'] = (new NoAccessEmployees())->execute();
        $data['accessLevelAdmin'] = (new GetAdminRoles())->execute();

        //
        $data['adminRole'] = (new GetAdminTypeRole())->execute();
        $data['employeeRoles'] = (new GetEmployeeTypeRoles())->execute();
        $data['managerRoles'] = (new GetManagerTypeRoles())->execute();
        $data['customRoles'] = (new GetCustomTypeRoles())->execute();
        $data['hrProRoles'] = (new GetHrProTypeRoles())->execute();

        return $data;
    }
}
