<?php

namespace App\Domain\Employee\Models;

use App\Domain\ACL\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Education extends Model
{
    protected $fillable = [
        'institute_name',
        'employee_id',
        'education_type_id',
        'major',
        'cgpa',
        'date_start',
        'date_end',
    ];
    public function EducationType()
    {
        return $this->belongsTo('App\Domain\Employee\Models\EducationType', 'education_type_id');
    }
    public function SecondaryLanguage()
    {
        return $this->belongsTo('App\Domain\Employee\Models\SecondaryLanguage', 'secondary_language_id');
    }
    //Custom method to get all default permissions related to Education Model
    public static function getDefaultPermissions($roleType, $education)
    {
        $role = Role::where('name', $roleType)->first();
        $permissions = DB::table('role_permission_has_access_levels')
            ->where('role_id', $role->id)->join('permissions', 'role_permission_has_access_levels.permission_id', '=', 'permissions.id')->get();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'education institute_name') !== false) {
                $education['personal']['education']['institute_name'][] = $permission->name;
            }
            if (stripos($permission->name, 'education major') !== false) {
                $education['personal']['education']['major'][] = $permission->name;
            }
            if (stripos($permission->name, 'education cgpa') !== false) {
                $education['personal']['education']['CGPA'][] = $permission->name;
            }
            if (stripos($permission->name, 'education date_start') !== false) {
                $education['personal']['education']['date_start'][] = $permission->name;
            }
            if (stripos($permission->name, 'education date_end') !== false) {
                $education['personal']['education']['date_end'][] = $permission->name;
            }
        }
        return $education;
    }

    //Get permissions of logged-in user related to Education Model.
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
                    if (stripos($permission->name, 'education institute_name') !== false) {
                        $education[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    } elseif (stripos($permission->name, 'education major') !== false) {
                        $education[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    } elseif (stripos($permission->name, 'education cgpa') !== false) {
                        $education[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    } elseif (stripos($permission->name, 'education date_start') !== false) {
                        $education[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    } elseif (stripos($permission->name, 'education date_end') !== false) {
                        $education[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                }
            }
        }
        return $education;
    }

    public static function getSelectedPermissions($roleType, $education)
    {
        $role = Role::findById($roleType);
        $permissions = $role->permissions()->withPivot('role_id')->where('role_id', $role->id)->get();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'education institute_name') !== false) {
                $education['personal']['education']['institute_name']['checked'] = $permission->name;
            }
            if (stripos($permission->name, 'education major') !== false) {
                $education['personal']['education']['major']['checked'] = $permission->name;
            }
            if (stripos($permission->name, 'education cgpa') !== false) {
                $education['personal']['education']['CGPA']['checked'] = $permission->name;
            }
            if (stripos($permission->name, 'education date_start') !== false) {
                $education['personal']['education']['date_start']['checked'] = $permission->name;
            }
            if (stripos($permission->name, 'education date_end') !== false) {
                $education['personal']['education']['date_end']['checked'] = $permission->name;
            }
        }
        return $education;
    }

    public static function sortPermissionKeys(&$defaultPermissions)
    {
        $education = $defaultPermissions['personal'];
        $sortedPermissions = [];
        !array_key_exists('education_type', $education['education']) ?: $sortedPermissions['personal']['education']['education_type'] = $education['education']['education_type'];
        !array_key_exists('institute_name', $education['education']) ?: $sortedPermissions['personal']['education']['institute_name'] = $education['education']['institute_name'];
        !array_key_exists('major', $education['education']) ?: $sortedPermissions['personal']['education']['major'] = $education['education']['major'];
        !array_key_exists('CGPA', $education['education']) ?: $sortedPermissions['personal']['education']['CGPA'] = $education['education']['CGPA'];
        !array_key_exists('date_start', $education['education']) ?: $sortedPermissions['personal']['education']['date_start'] = $education['education']['date_start'];
        !array_key_exists('date_end', $education['education']) ?: $sortedPermissions['personal']['education']['date_end'] = $education['education']['date_end'];
        $defaultPermissions['personal']['education'] = $sortedPermissions['personal']['education'];
        return $defaultPermissions;
    }
}
