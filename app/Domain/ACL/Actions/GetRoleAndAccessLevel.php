<?php

namespace App\Domain\ACL\Actions;

use App\Domain\ACL\Models\Role;
use Illuminate\Support\Facades\DB;

class GetRoleAndAccessLevel
{
    public function execute($id)
    {
        $role['role'] = Role::where('id', $id)->first();
        try {
            $role['accessLevel'] = $role['role']->permissions()->withPivot('role_id')->first()->pivot->access_level_id;
            return $role;
        } catch (\Throwable $th) {
            //throw $th;
        }
        $role['accessLevel'] = DB::table('access_levels')->where('name', 'self')->pluck('id')->first();
        return $role;
    }
}
