<?php

namespace App\Domain\Employee\Models;

use App\Domain\ACL\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmployeeJob extends Model
{
    protected $fillable=['effective_date', 'employee_id', 'designation_id', 'report_to', 'department_id', 'division_id', 'location_id'];

    public function location()
    {
        return $this->belongsTo('App\Domain\Employee\Models\Location', 'location_id');
    }
    public function manager()
    {
        return $this->belongsTo('App\Domain\Employee\Models\Employee', 'report_to');
    }
    public function designation()
    {
        return $this->belongsTo('App\Domain\Employee\Models\Designation', 'designation_id');
    }
    public function department()
    {
        return $this->belongsTo('App\Domain\Employee\Models\Department', 'department_id');
    }
    public function division()
    {
        return $this->belongsTo('App\Domain\Employee\Models\Division', 'division_id');
    }

    //Custom method to get all default permissions related to Employee EmploymentStatus Model
    public static function getDefaultPermissions($roleType, $job)
    {
        $role = Role::where('type', $roleType)->first();
        $permissions = DB::table('role_permission_has_access_levels')
            ->where('role_id', $role->id)->join('permissions', 'role_permission_has_access_levels.permission_id', '=', 'permissions.id')->get();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'employeejob effective_date') !== false) {
                $job['job']['job_information']['date'][] = $permission->name;
            } elseif (stripos($permission->name, 'employeejob designation_id') !== false) {
                $job['job']['job_information']['job_title'][] = $permission->name;
            } elseif (stripos($permission->name, 'employeejob report_to') !== false) {
                $job['job']['job_information']['reporting_to'][] = $permission->name;
            } elseif (stripos($permission->name, 'employeejob department_id') !== false) {
                $job['job']['job_information']['department'][] = $permission->name;
            } elseif (stripos($permission->name, 'employeejob division_id') !== false) {
                $job['job']['job_information']['division'][] = $permission->name;
            } elseif (stripos($permission->name, 'employeejob location_id') !== false) {
                $job['job']['job_information']['location'][] = $permission->name;
            }
        }
        return $job;
    }

    //Get permissions of logged-in user related to Employee Visa Model.
    public static function getPermissionsWithAccessLevel($user)
    {
        $job = null;
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
                    if (stripos($permission->name, 'employeejob effective_date') !== false) {
                        $job[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    } elseif (stripos($permission->name, 'employeejob designation_id') !== false) {
                        $job[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                    elseif (stripos($permission->name, 'employeejob report_to') !== false) {
                        $job[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                    elseif (stripos($permission->name, 'employeejob department_id') !== false) {
                        $job[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                    elseif (stripos($permission->name, 'employeejob division_id') !== false) {
                        $job[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                    elseif (stripos($permission->name, 'employeejob location_id') !== false) {
                        $job[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                }
            }
        }

        return $job;
    }

    public static function getSelectedPermissions($roleType, $job)
    {
        $role = Role::findById($roleType);
        $permissions = $role->permissions()->withPivot('role_id')->where('role_id', $role->id)->get();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'employeejob effective_date') !== false) {
                $job['job']['job_information']['date']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'employeejob designation_id') !== false) {
                $job['job']['job_information']['job_title']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'employeejob report_to') !== false) {
                $job['job']['job_information']['reporting_to']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'employeejob department_id') !== false) {
                $job['job']['job_information']['department']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'employeejob division_id') !== false) {
                $job['job']['job_information']['division']['checked'] = $permission->name;
            } elseif (stripos($permission->name, 'employeejob location_id') !== false) {
                $job['job']['job_information']['location']['checked'] = $permission->name;
            }
        }
        return $job;
    }
}
