<?php


namespace App\Domain\Employee\Actions;

class GetAllEmployeeRoles
{

    /**
     * @param $roles
     * @return array
     */
    public function execute($roles)
    {
        $employeeRoles = [];
        foreach ($roles as $role) {
            if ($role->type == 'employee') {
                $employeeRoles[] = $role;
            }
        }
        return $employeeRoles;
    }
}
