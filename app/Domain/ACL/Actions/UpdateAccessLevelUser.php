<?php

namespace App\Domain\ACL\Actions;

use Illuminate\Support\Facades\Session;

class UpdateAccessLevelUser
{
    public function execute($request, $roleId, $employeeId)
    {
        $result = (new updateEmployeeInRole())->execute($request, $roleId, $employeeId);
        $role = (new GetRole())->execute($roleId);
        if ($result) {
            $role = ($role == null) ? 'No access' : $role->name;
            Session::flash('success', trans('Employee Added successfully in the ' . $role));
        } else {
            $role = ($role == null) ? 'No access' : $role->name;
            Session::flash('error', trans('Unable to add employee in ' . $role));
        }
    }
}
