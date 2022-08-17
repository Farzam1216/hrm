<?php


namespace App\Domain\Employee\Actions;

use App\Domain\Employee\Models\Employee;


class CheckUserHasHrManagerRole
{
    public function execute()
    {
        $result = false;
        $employeeRoles = Employee::find(auth()->user()->id)->roles;
        foreach ($employeeRoles as $role) {
            if ($role->type == 'hr-pro') {
                $result = true;
            }
        }

        return $result;
    }
}
