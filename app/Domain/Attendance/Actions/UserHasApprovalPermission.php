<?php


namespace App\Domain\Attendance\Actions;


use App\Domain\ACL\Models\Role;
use App\Domain\Attendance\Models\EmployeeAttendanceComments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserHasApprovalPermission
{
    /**
     * add available plans in benefitGroup
     * @param $request
     */
    public function execute()
    {
        $hasApprovalPermission = false;
        foreach (\auth()->user()->roles as $role) {
            if ($role->type == 'hr-pro') {
                $role = Role::where('name', Auth::user()->roles->pluck('name')[0])->first();
                $permissions = DB::table('role_permission_has_access_levels')
                    ->where('role_id', $role->id)->join('permissions', 'role_permission_has_access_levels.permission_id', '=', 'permissions.id')->get();
                foreach ($permissions as $permission) {
                    $perm = json_encode($permission, true);
                    $perm = json_decode($perm, true);
                    if ($perm['name'] == 'manage employees attendance') {
                        $hasApprovalPermission = true;
                        break;
                    }
                }
            }
        }

        return $hasApprovalPermission;
    }
}
