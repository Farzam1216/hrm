<?php

namespace App\Domain\ACL\Actions;

use App\Domain\ACL\Models\Role;
use Illuminate\Support\Facades\DB;

class DestroyRole
{
    /**
     * Remove the specified role from database.
     *
     * @param  int  $id
     * @return bool
     */
    public function execute($id): bool
    {
        $role = Role::where('id', $id)->first();
        $employeesInRole = DB::table('model_has_roles')->where('role_id', $role->id)->get();
        if ($employeesInRole->count() > 0) {
            return false;
        } else {
            $role->delete();
            return true;
        }
    }
}
