<?php

namespace App\Domain\Benefit\Models;

use App\Domain\ACL\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmployeeDependent extends Model
{
    protected $fillable = [
        'employee_id',
        'first_name',
        'middle_name',
        'last_name',
        'SSN',
        'SIN',
        'relationship',
        'gender',
        'date_of_birth',
        'street1',
        'street2',
        'city',
        'state',
        'zip',
        'country',
        'address',
        'is_us_citizen',
        'is_student',
        'home_phone',
    ];

    //Custom method to get all default permissions related to Employee Dependents Model
    public static function getDefaultPermissions($roleType, $employeeDependents)
    {
        $role = Role::where('type', $roleType)->first();
        $permissions = DB::table('role_permission_has_access_levels')
            ->where('role_id', $role->id)->join('permissions', 'role_permission_has_access_levels.permission_id', '=', 'permissions.id')->get();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'employeedependent first_name') !== false) {
                $employeeDependents['benefits']['dependents']['first_name'][] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent middle_name') !== false) {
                $employeeDependents['benefits']['dependents']['middle_name'][] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent last_name') !== false) {
                $employeeDependents['benefits']['dependents']['last_name'][] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent SSN') !== false) {
                $employeeDependents['benefits']['dependents']['SSN'][] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent SIN') !== false) {
                $employeeDependents['benefits']['dependents']['SIN'][] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent date_of_birth') !== false) {
                $employeeDependents['benefits']['dependents']['date_of_birth'][] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent relationship') !== false) {
                $employeeDependents['benefits']['dependents']['relationship'][] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent gender') !== false) {
                $employeeDependents['benefits']['dependents']['gender'][] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent address') !== false) {
                $employeeDependents['benefits']['dependents']['address'][] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent is_us_citizen') !== false) {
                $employeeDependents['benefits']['dependents']['is_us_citizen'][] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent is_student') !== false) {
                $employeeDependents['benefits']['dependents']['is_student'][] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent street1') !== false) {
                $employeeDependents['benefits']['dependents']['street1'][] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent street2') !== false) {
                $employeeDependents['benefits']['dependents']['street2'][] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent city') !== false) {
                $employeeDependents['benefits']['dependents']['city'][] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent state') !== false) {
                $employeeDependents['benefits']['dependents']['state'][] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent zip') !== false) {
                $employeeDependents['benefits']['dependents']['zip'][] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent country') !== false) {
                $employeeDependents['benefits']['dependents']['country'][] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent home_phone') !== false) {
                $employeeDependents['benefits']['dependents']['home_phone'][] = $permission->name;
            }
        }
        return $employeeDependents;
    }
    //Get permissions of logged-in user related to Employee Dependent Model.
    public static function getPermissionsWithAccessLevel($user)
    {
        $employeeDependent = null;
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
                    $employeeDependent = (new EmployeeDependent())->groupPermissions($role, $permission, $employeeDependent);
                }
            }
        }
        return $employeeDependent;
    }

    //Group Permissions depending upon model fields
    public function groupPermissions($role, $permission, $employeeDependent)
    {
        foreach ((new EmployeeDependent)->fillable as $field) {
            if (stripos($permission->name, 'employeedependent ' . $field) !== false) {
                $employeeDependent[$role->id][$permission->pivot->access_level_id][] = $permission->name;
            }
        }
        return $employeeDependent;
    }

    public static function getSelectedPermissions($roleType, $employeeDependents)
    {
        $role = Role::findById($roleType);
        $permissions = $role->permissions()->withPivot('role_id')->where('role_id', $role->id)->get();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'employeedependent first_name') !== false) {
                $employeeDependents['benefits']['dependents']['first_name']['checked'] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent middle_name') !== false) {
                $employeeDependents['benefits']['dependents']['middle_name']['checked'] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent last_name') !== false) {
                $employeeDependents['benefits']['dependents']['last_name']['checked'] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent SSN') !== false) {
                $employeeDependents['benefits']['dependents']['SSN']['checked'] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent SIN') !== false) {
                $employeeDependents['benefits']['dependents']['SIN']['checked'] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent date_of_birth') !== false) {
                $employeeDependents['benefits']['dependents']['date_of_birth']['checked'] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent relationship') !== false) {
                $employeeDependents['benefits']['dependents']['relationship']['checked'] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent gender') !== false) {
                $employeeDependents['benefits']['dependents']['gender']['checked'] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent address') !== false) {
                $employeeDependents['benefits']['dependents']['address']['checked'] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent is_us_citizen') !== false) {
                $employeeDependents['benefits']['dependents']['is_us_citizen']['checked'] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent is_student') !== false) {
                $employeeDependents['benefits']['dependents']['is_student']['checked'] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent street1') !== false) {
                $employeeDependents['benefits']['dependents']['street1']['checked'] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent street2') !== false) {
                $employeeDependents['benefits']['dependents']['street2']['checked'] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent city') !== false) {
                $employeeDependents['benefits']['dependents']['city']['checked'] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent state') !== false) {
                $employeeDependents['benefits']['dependents']['state']['checked'] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent zip') !== false) {
                $employeeDependents['benefits']['dependents']['zip']['checked'] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent country') !== false) {
                $employeeDependents['benefits']['dependents']['country']['checked'] = $permission->name;
            }
            if (stripos($permission->name, 'employeedependent home_phone') !== false) {
                $employeeDependents['benefits']['dependents']['home_phone']['checked'] = $permission->name;
            }
        }
        return $employeeDependents;
    }

    public static function sortPermissionKeys(&$defaultPermissions)
    {
        if (isset($defaultPermissions['benefits'])) {
            $benefits = $defaultPermissions['benefits'];
            $sortedPermissions = [];
            !array_key_exists('first_name', $benefits['dependents']) ?: $sortedPermissions['benefits']['dependents']['first_name'] = $benefits['dependents']['first_name'];
            !array_key_exists('middle_name', $benefits['dependents']) ?: $sortedPermissions['benefits']['dependents']['middle_name'] = $benefits['dependents']['middle_name'];
            !array_key_exists('last_name', $benefits['dependents']) ?: $sortedPermissions['benefits']['dependents']['last_name'] = $benefits['dependents']['last_name'];
            !array_key_exists('date_of_birth', $benefits['dependents']) ?: $sortedPermissions['benefits']['dependents']['date_of_birth'] = $benefits['dependents']['date_of_birth'];
            !array_key_exists('SSN', $benefits['dependents']) ?: $sortedPermissions['benefits']['dependents']['SSN'] = $benefits['dependents']['SSN'];
            !array_key_exists('SIN', $benefits['dependents']) ?: $sortedPermissions['benefits']['dependents']['SIN'] = $benefits['dependents']['SIN'];
            !array_key_exists('gender', $benefits['dependents']) ?: $sortedPermissions['benefits']['dependents']['gender'] = $benefits['dependents']['gender'];
            !array_key_exists('relationship', $benefits['dependents']) ?: $sortedPermissions['benefits']['dependents']['relationship'] = $benefits['dependents']['relationship'];
            !array_key_exists('street1', $benefits['dependents']) ?: $sortedPermissions['benefits']['dependents']['street1'] = $benefits['dependents']['street1'];
            !array_key_exists('street2', $benefits['dependents']) ?: $sortedPermissions['benefits']['dependents']['street2'] = $benefits['dependents']['street2'];
            !array_key_exists('city', $benefits['dependents']) ?: $sortedPermissions['benefits']['dependents']['city'] = $benefits['dependents']['city'];
            !array_key_exists('state', $benefits['dependents']) ?: $sortedPermissions['benefits']['dependents']['state'] = $benefits['dependents']['state'];
            !array_key_exists('zip', $benefits['dependents']) ?: $sortedPermissions['benefits']['dependents']['zip'] = $benefits['dependents']['zip'];
            !array_key_exists('country', $benefits['dependents']) ?: $sortedPermissions['benefits']['dependents']['country'] = $benefits['dependents']['country'];
            !array_key_exists('home_phone', $benefits['dependents']) ?: $sortedPermissions['benefits']['dependents']['home_phone'] = $benefits['dependents']['home_phone'];
            !array_key_exists('is_us_citizen', $benefits['dependents']) ?: $sortedPermissions['benefits']['dependents']['is_us_citizen'] = $benefits['dependents']['is_us_citizen'];
            !array_key_exists('is_student', $benefits['dependents']) ?: $sortedPermissions['benefits']['dependents']['is_student'] = $benefits['dependents']['is_student'];
            $defaultPermissions['benefits']['dependents'] = $sortedPermissions['benefits']['dependents'];
        }
        return $defaultPermissions;
    }
}
