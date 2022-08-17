<?php

namespace App\Domain\Employee\Models;

use App\Domain\ACL\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Asset extends Model
{
    protected $fillable = [
        'employee_id', 'asset_category', 'asset_description', 'serial', 'assign_date', 'return_date'
    ];

    public function asset_type()
    {
        return $this->belongsTo('App\Domain\Employee\Models\AssetsType', 'asset_category');
    }
    public function employee()
    {
        return $this->belongsTo('App\Domain\Employee\Models\Employee', 'employee_id', 'id');
    }
    //Custom method to get all default permissions related to Asset Model
    public static function getDefaultPermissions($roleType, $assets)
    {
        $role = Role::where('name', $roleType)->first();
        $permissions = DB::table('role_permission_has_access_levels')
            ->where('role_id', $role->id)->join('permissions', 'role_permission_has_access_levels.permission_id', '=', 'permissions.id')->get();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'asset asset_category') !== false) {
                $assets['assets']['assets']['asset_category'][] = $permission->name;
            }
            if (stripos($permission->name, 'asset asset_description') !== false) {
                $assets['assets']['assets']['asset_description'][] = $permission->name;
            }
            if (stripos($permission->name, 'asset serial') !== false) {
                $assets['assets']['assets']['serial'][] = $permission->name;
            }
            if (stripos($permission->name, 'asset assign_date') !== false) {
                $assets['assets']['assets']['assign_date'][] = $permission->name;
            }
        }
        return $assets;
    }

    //Get permissions of logged-in user related to Asset Model.
    public static function getPermissionsWithAccessLevel($user)
    {
        $assets = null;
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
                    $assets = (new Asset())->groupPermissions($role, $permission, $assets);
                }
            }
        }
        return $assets;
    }

    //Group Permissions depending upon model fields
    public function groupPermissions($role, $permission, $assets)
    {
        foreach ((new Asset())->fillable as $field) {
            if (stripos($permission->name, 'asset ' . $field) !== false) {
                $assets[$role->id][$permission->pivot->access_level_id][] = $permission->name;
            }
        }
        return $assets;
    }
    public static function getSelectedPermissions($roleType, $assets)
    {
        $role = Role::findById($roleType);
        $permissions = $role->permissions()->withPivot('role_id')->where('role_id', $role->id)->get();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'asset asset_category') !== false) {
                $assets['assets']['assets']['asset_category']['checked'] = $permission->name;
            }
            if (stripos($permission->name, 'asset asset_description') !== false) {
                $assets['assets']['assets']['asset_description']['checked'] = $permission->name;
            }
            if (stripos($permission->name, 'asset serial') !== false) {
                $assets['assets']['assets']['serial']['checked'] = $permission->name;
            }
            if (stripos($permission->name, 'asset assign_date') !== false) {
                $assets['assets']['assets']['assign_date']['checked'] = $permission->name;
            }
        }
        return $assets;
    }
}
