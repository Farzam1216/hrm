<?php

namespace App\Domain\ACL\Actions;

use App\Domain\Employee\Models\Employee;

class UpdateEmployeeInRole
{
    /**
     * Adding employees to role
     *
     * @param  Request request
     * @param  integer id
     * @return void
     */
    public function execute($request, $id, $employeeId = null)
    {
        $role =  (new GetRole())->execute($id);
        if (is_array($request->employees_Id)) {
            foreach ($request->employees_Id as $employee) {
                $user = Employee::where('id', $employee)->where('id', '!=', 1)->where('status', '!=', 0)->first();
                if (collect($user)->isNotEmpty()) {
                    $user->syncRoles([$role->name]);
                }
            }
            return true;
        } else {
            $user = Employee::where('id', $employeeId)->where('status', '!=', 0)->first();
            if (collect($user)->isNotEmpty()) {
                if ($id == 'noaccess') {
                    $user->roles()->detach();
                    return true;
                }
                $user->syncRoles([$role->name]);
                return true;
            }
        }
    }
}
