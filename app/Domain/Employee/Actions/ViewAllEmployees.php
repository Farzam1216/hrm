<?php


namespace App\Domain\Employee\Actions;

class ViewAllEmployees
{
    public function execute($employee_id)
    {
        $data['info'] = (new GetAllEmployeesWithJobDetails())->execute($employee_id);
        $data['permissions'] = (new GetAuthorizedUserPermissions())->execute($data['info']['employee']);
        $data['basicPermissions'] = (new PermissionsToAccessEmployeeInformation())->execute($data['permissions']);
        $data['hr-manager-permission'] = (new CheckUserHasHrManagerRole())->execute();
        return $data;
    }
}
