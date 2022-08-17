<?php

namespace App\Models;

use App\Domain\ACL\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Country extends Model
{
    /**
     * @return HasMany
     */
    public function Country()
    {
        return $this->hasMany('App\Domain\Employee\Models\EmployeeVisa', 'country_id', 'id');
    }
    //Custom method to get all default permissions related to Country Model
    public static function getDefaultPermissions($roleType, $visa)
    {
        $role = Role::where('name', $roleType)->first();
        $permissions = DB::table('role_permission_has_access_levels')
            ->where('role_id', $role->id)->join('permissions', 'role_permission_has_access_levels.permission_id', '=', 'permissions.id')->get();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'country name') !== false) {
                $visa['personal']['visa']['name'][] = $permission->name;
            }
        }
        return $visa;
    }
    //Get permissions of logged-in user related to Country Model.
    public static function getPermissionsWithAccessLevel($user)
    {
        $country = null;
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
                    if (stripos($permission->name, 'country name') !== false) {
                        $country[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                }
            }
        }
        return $country;
    }
    public static function getSelectedPermissions($roleType, $visa)
    {
        $role = Role::findById($roleType);
        $permissions = $role->permissions()->withPivot('role_id')->where('role_id', $role->id)->get();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'country name') !== false) {
                $visa['personal']['visa']['name']['checked'] = $permission->name;
            }
        }
        return $visa;
    }
}
