<?php

namespace App\Domain\Employee\Models;

use App\Domain\ACL\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmployeeVisa extends Model
{
    protected $fillable = [
        'visa_type_id',
        'country_id',
        'issue_date',
        'expire_date',
        'note',
        'employee_id'
    ];

    public function Visa()
    {
        return $this->belongsTo('App\Domain\Employee\Models\Employee', 'employee_id', 'id');
    }
    public function VisaType()
    {
        return $this->belongsTo('App\Domain\Employee\Models\VisaType', 'visa_type_id');
    }
    public function Country()
    {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }

    //Custom method to get all default permissions related to Employee Visa Model
    public static function getDefaultPermissions($roleType, $visa)
    {
        $role = Role::where('name', $roleType)->first();
        $permissions = DB::table('role_permission_has_access_levels')
            ->where('role_id', $role->id)->join('permissions', 'role_permission_has_access_levels.permission_id', '=', 'permissions.id')->get();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'employeevisa country_id') !== false) {
                $visa['personal']['visa']['issuing_country'][] = $permission->name;
            } elseif (stripos($permission->name, 'employeevisa issue_date') !== false) {
                $visa['personal']['visa']['issue_date'][] = $permission->name;
            } elseif (stripos($permission->name, 'employeevisa expire_date') !== false) {
                $visa['personal']['visa']['expire_date'][] = $permission->name;
            } elseif (stripos($permission->name, 'employeevisa note') !== false) {
                $visa['personal']['visa']['note'][] = $permission->name;
            }
        }
        return $visa;
    }

    //Get permissions of logged-in user related to Employee Visa Model.
    public static function getPermissionsWithAccessLevel($user)
    {
        $employeeVisa = null;
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
                    if (stripos($permission->name, 'employeevisa issue_date') !== false) {
                        $employeeVisa[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    } elseif (stripos($permission->name, 'employeevisa expire_date') !== false) {
                        $employeeVisa[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    } elseif (stripos($permission->name, 'employeevisa country_id') !== false) {
                        $employeeVisa[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    } elseif (stripos($permission->name, 'employeevisa note') !== false) {
                        $employeeVisa[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                }
            }
        }

        return $employeeVisa;
    }

    public static function getSelectedPermissions($roleType, $visa)
    {
        $role = Role::findById($roleType);
        $permissions = $role->permissions()->withPivot('role_id')->where('role_id', $role->id)->get();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'employeevisa country_id') !== false) {
                $visa['personal']['visa']['issuing_country']['checked'] = $permission->name;
            }
            if (stripos($permission->name, 'employeevisa issue_date') !== false) {
                $visa['personal']['visa']['issue_date']['checked'] = $permission->name;
            }
            if (stripos($permission->name, 'employeevisa expire_date') !== false) {
                $visa['personal']['visa']['expire_date']['checked'] = $permission->name;
            }
            if (stripos($permission->name, 'employeevisa note') !== false) {
                $visa['personal']['visa']['note']['checked'] = $permission->name;
            }
        }
        return $visa;
    }

    public static function sortPermissionKeys(&$defaultPermissions)
    {
        $visa = $defaultPermissions['personal'];
        $sortedPermissions = [];
        !array_key_exists('visa_type', $visa['visa']) ?: $sortedPermissions['personal']['visa']['visa_type'] = $visa['visa']['visa_type'];
        !array_key_exists('issuing_country', $visa['visa']) ?: $sortedPermissions['personal']['visa']['issuing_country'] = $visa['visa']['issuing_country'];
        !array_key_exists('issue_date', $visa['visa']) ?: $sortedPermissions['personal']['visa']['issue_date'] = $visa['visa']['issue_date'];
        !array_key_exists('expire_date', $visa['visa']) ?: $sortedPermissions['personal']['visa']['expire_date'] = $visa['visa']['expire_date'];
        !array_key_exists('note', $visa['visa']) ?: $sortedPermissions['personal']['visa']['note'] = $visa['visa']['note'];

        $defaultPermissions['personal']['visa'] = $sortedPermissions['personal']['visa'];
        return $defaultPermissions;
    }

}
