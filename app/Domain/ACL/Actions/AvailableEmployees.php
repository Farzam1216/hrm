<?php

namespace App\Domain\ACL\Actions;

use App\Domain\Employee\Models\Employee;

class AvailableEmployees
{
    /**
     * Available employees for assigning role
     *
     * @param  integer roleID
     * @param  string employee
     * @return Collection allEmployee
     */
    public function execute($roleID, $employees)
    {
        $allEmployee = Employee::where('status', '!=', 0)->get();
        return  $allEmployee->diff($employees);
    }
}
