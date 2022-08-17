<?php

namespace App\Domain\ACL\Actions;

use App\Domain\Employee\Models\Employee;

class EditAccessLevel
{
    public function execute($id)
    {
        $data['employeeRoles'] = (new GetEmployeeTypeRoles())->execute();
        $data['managerRoles'] = (new GetManagerTypeRoles())->execute();
        $data['customRoles'] = (new GetCustomTypeRoles())->execute();
        $data['employee'] = Employee::find($id);
        return $data;
    }
}
