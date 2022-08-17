<?php

namespace App\Domain\Employee\Models;

use App\Domain\ACL\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SecondaryLanguage extends Model
{
    protected $fillable = [
        'language_name', 'status'
    ];
    public function SecondaryLanguage()
    {
        return $this->hasMany('App\Domain\Employee\Models\Education', 'secondary_language_id');
    }
    //Custom method to get all default permissions related to Education Secondary language Model
    public static function getDefaultPermissions($roleType, $language)
    {
        $role = Role::where('name', $roleType)->first();
        $permissions = DB::table('role_permission_has_access_levels')
            ->where('role_id', $role->id)->join('permissions', 'role_permission_has_access_levels.permission_id', '=', 'permissions.id')->get();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'secondarylanguage language_name') !== false) {
                $language['personal']['education']['language_name'][] = substr($permission->name, 0, strrpos($permission->name, " "));
            }
        }
        return $language;
    }

    //Get permissions of logged-in user related to Secondary Language Model.
    public static function getPermissionsWithAccessLevel($user)
    {
        $secondaryLanguage = null;
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
                    if (stripos($permission->name, 'secondarylanguage language_name') !== false) {
                        $secondaryLanguage[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                }
            }
        }

        return $secondaryLanguage;
    }
}
