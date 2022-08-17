<?php

namespace App\Domain\ACL\Actions;

use App\Domain\ACL\Models\Role;
use App\Domain\Employee\Models\Employee;

class assignMultipleRoles
{
    /**
     * Assign multiple roles to employee.
     *
     * @param  Request  $request
     * @return bool
     */
    public function execute($request)
    {
        $user = Employee::where('id', $request->employee)->where('status', '!=', 0)->first();
        if (collect($user)->isNotEmpty()) {
            $roles = Role::whereIn('id', $request->multiple_access_levels)->get();
            $user->syncRoles($roles->pluck('name')->toArray());
            return true;
        }
        return false;
    }
}
