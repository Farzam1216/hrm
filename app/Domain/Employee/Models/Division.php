<?php

namespace App\Domain\Employee\Models;

use App\Domain\ACL\Models\Role;
use App\Domain\Task\Models\TaskRequiredForFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Division extends Model
{
    protected $fillable = [
        'name', 'status'
    ];

    /**
     * Get the division based filter of task template.
     */
    public function task_required_for_filter()
    {
        return $this->morphOne(TaskRequiredForFilter::class, 'filter');
    }

    //Custom method to get all default permissions related to Division Model
    public static function getDefaultPermissions($roleType, $division)
    {
        $role = Role::where('name', $roleType)->first();
        $permissions = DB::table('role_has_permissions')
            ->where('role_id', $role->id)->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')->get();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'division name') !== false) {
                $division['job']['no_group']['name'][] = substr($permission->name, 0, strrpos($permission->name, " "));
            }
        }
        return $division;
    }

    //Get permissions of logged-in user related to Division Model.
    public static function getPermissionsWithAccessLevel($user)
    {
        $division = null;
        $roles = $user->roles()->get();
        foreach ($roles as $role) {
            $roleWithSubRole[] = $role;
            //If user is not an "employee" he might have a sub_role (employee) also
            if ($role->type != "employee") {
                if (isset($role->sub_role)) {
                    $roleWithSubRole[] = Role::where('id', $role->sub_role)->first();
                }
            }
            foreach ($roleWithSubRole as $role) {
                $permissions = $role->permissions()->get();
                foreach ($permissions as $permission) {
                    if (stripos($permission->name, 'division name') !== false) {
                        $division[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                }
            }
        }
        return $division;
    }
}
