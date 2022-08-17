<?php

namespace App\Domain\ACL\Actions;

use App\Domain\Employee\Models\Employee;
use Illuminate\Support\Facades\DB;

class GetSpecificEmployeesInCustomRole
{
    /*
     * Return specificEmployee for custom permissions
     * @param $role
     * @return Collection
     */
    public function execute($role)
    {
        $allPermissions = DB::table('custom_role_permissions')->where('role_id', $role)->get();
        $employees = $allPermissions->pluck('employee_id')->unique();
        return Employee::whereIn('id', $employees)->get();
    }
}
