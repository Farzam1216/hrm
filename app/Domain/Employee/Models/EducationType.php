<?php

namespace App\Domain\Employee\Models;

use App\Domain\ACL\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EducationType extends Model
{
    protected $fillable = [
        'education_type', 'status'
    ];
    public function Education()
    {
        return $this->hasMany('App\Domain\Employee\Models\Education', 'education_type_id');
    }
    //Custom method to get all default permissions related to Education Type Model
    public static function getDefaultPermissions($roleType, $educationType)
    {
        $role = Role::where('name', $roleType)->first();

        $permissions = DB::table('role_permission_has_access_levels')
            ->where('role_id', $role->id)->join('permissions', 'role_permission_has_access_levels.permission_id', '=', 'permissions.id')->get();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'educationtype education_type') !== false) {
                $educationType['personal']['education']['education_type'][] = $permission->name;
            }
        }
        return $educationType;
    }

    public static function getSelectedPermissions($roleType, $educationType)
    {
        $role = Role::findById($roleType);
        $permissions = $role->permissions()->withPivot('role_id')->where('role_id', $role->id)->get();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'educationtype education_type') !== false) {
                $educationType['personal']['education']['education_type'][] = $permission->name;
            }
        }
        return $educationType;
    }

    //Get permissions of logged-in user related to Education Type Model.
    public static function getPermissionsWithAccessLevel($user)
    {
        $education = null;
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
                    if (stripos($permission->name, 'educationtype education_type') !== false) {
                        $education[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                }
            }
        }

        return $education;
    }
}
