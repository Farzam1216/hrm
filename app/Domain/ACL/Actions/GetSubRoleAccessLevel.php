<?php

namespace App\Domain\ACL\Actions;

use Illuminate\Support\Facades\DB;

class GetSubRoleAccessLevel
{
    /**
     * Return specificEmployee for custom permissions
     * @param $role
     * @return Collection
     */
    public function execute($role)
    {
        $subRole = DB::table('role_permission_has_access_levels')->where('role_id', $role)->where('access_level_id', 0)->first();
        return ($subRole != null) ? true : false;
    }
}
