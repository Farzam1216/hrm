<?php

namespace App\Domain\Employee\Models;

use App\Domain\ACL\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmployeeEmploymentStatus extends Model
{
    protected $fillable = ['employee_id', 'effective_date', 'employment_status_id', 'comment'];

    public function employmentStatus()
    {
        return $this->belongsTo('App\Domain\Employee\Models\EmploymentStatus', 'employment_status_id');
    }

    public function employee()
    {
        return $this->belongsTo('App\Domain\Employee\Models\Employee', 'employee_id');
    }

    //Custom method to get all default permissions related to Employee EmploymentStatus Model
    public static function getDefaultPermissions($roleType, $employmentStatus)
    {
        $role = Role::where('name', $roleType)->first();
        $permissions = DB::table('role_permission_has_access_levels')
            ->where('role_id', $role->id)->join('permissions', 'role_permission_has_access_levels.permission_id', '=', 'permissions.id')->get();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'employeeemploymentstatus effective_date') !== false) {
                $employmentStatus['job']['employment_status']['effective_date'][] = $permission->name;
            } elseif (stripos($permission->name, 'employeeemploymentstatus comments') !== false) {
                $employmentStatus['job']['employment_status']['comments'][] = $permission->name;
            } elseif (stripos($permission->name, 'employeeemploymentstatus employment_status_id') !== false) {
                $employmentStatus['job']['employment_status']['employment_status'][] = $permission->name;
            }
        }
        return $employmentStatus;
    }

    //Get permissions of logged-in user related to Employee Visa Model.
    public static function getPermissionsWithAccessLevel($user)
    {
        $employmentStatus = null;
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
                    if (stripos($permission->name, 'employeeemploymentstatus effective_date') !== false) {
                        $employmentStatus[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    } elseif (stripos($permission->name, 'employeeemploymentstatus comments') !== false) {
                        $employmentStatus[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                    elseif (stripos($permission->name, 'employeeemploymentstatus employment_status_id') !== false) {
                        $employmentStatus[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                }
            }
        }

        return $employmentStatus;
    }

    public static function getSelectedPermissions($roleType, $employmentStatus)
    {
        $role = Role::findById($roleType);
        $permissions = $role->permissions()->withPivot('role_id')->where('role_id', $role->id)->get();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'employeeemploymentstatus effective_date') !== false) {
                $employmentStatus['job']['employment_status']['effective_date']['checked'] = $permission->name;
            }
            if (stripos($permission->name, 'employeeemploymentstatus comments') !== false) {
                $employmentStatus['job']['employment_status']['comments']['checked'] = $permission->name;
            }
            if (stripos($permission->name, 'employeeemploymentstatus employment_status_id') !== false) {
                $employmentStatus['job']['employment_status']['employment_status']['checked'] = $permission->name;
            }
        }
        return $employmentStatus;
    }
}
