<?php

namespace App\Traits;

use App\Domain\ACL\Models\Permission;
use App\Domain\ACL\Models\Role;
use Auth;
use Illuminate\Support\Facades\DB;

trait AccessibleFields
{
    /**
     * Get a list of permissions for Authorized user against the given employee/employees list
     */
    public function getAccessibleFieldList($model, $employees)
    {
        $allowedPermissions = [];
        //Get all permissions with role and access level details for given Model
        $allPermissionsDetails = $model::getPermissionsWithAccessLevel(Auth::user());

        if (isset($allPermissionsDetails)) {
            foreach ($employees as $employee) {
                $allowedPermissions = $this->getAllowedPermissionsOverEmployee($employee, $allPermissionsDetails, $allowedPermissions);
            }
        }
        return $allowedPermissions;
    }

    /**
     * Get list of permissions from given permissions which authenticated user has over $employee
     */
    public function getAllowedPermissionsOverEmployee($employee, $allPermissionsDetails, $allowedPermissions)
    {
        foreach ($allPermissionsDetails as $role_id => $accessLevelsAndPermissions) {
            foreach ($accessLevelsAndPermissions as $accessLevel => $permissions) {
                foreach ($permissions as $permission) {
                    $allowedPermissions = $this->checkPermissionForGivenAccessLevel($permission, $accessLevel, $employee, $role_id, $allowedPermissions);
                }
            }
        }
        return $allowedPermissions;
    }

    /**
     *
     * Check if authenticated user has the $permission for given access level and return if exists
     *
     */
    public function checkPermissionForGivenAccessLevel($permission, $accessLevel, $employee, $role_id, $allowedPermissions)
    {
        $accessLevelLabel = $this->getAccessLevelLabel($accessLevel);
        if (isset($employee)) {
            switch ($accessLevelLabel) {
                case "self":
                    if ($this->isAuthenticatedEmployee($employee->id)) {
                        $allowedPermissions = $this->pushPermissionInAllowedPermissions($permission, $employee->id, $allowedPermissions);
                    }
                    break;
                case "direct":
                    if ($this->isDirectEmployee($employee->id)) {
                        $allowedPermissions = $this->pushPermissionInAllowedPermissions($permission, $employee->id, $allowedPermissions);
                    }
                    break;
                case "directIndirect":
                    if ($this->isDirectOrIndirectEmployee($employee->id)) {
                        $allowedPermissions = $this->pushPermissionInAllowedPermissions($permission, $employee->id, $allowedPermissions);
                    }
                    break;
                case "fixed":
                    if ($this->hasPermissionOverEmployee($permission, $employee->id, $role_id)) {
                        $allowedPermissions = $this->pushPermissionInAllowedPermissions($permission, $employee->id, $allowedPermissions);
                    }

                    break;
                case "all":
                    if (!($this->isAuthenticatedEmployee($employee->id))) {
                        if (strtok($permission, " ") == "request") {
                            $allowedPermissions[$employee->id][$permission] = strtok($permission, " ");
                        } else {
                            $allowedPermissions[$employee->id][substr(strstr($permission, " "), 1)] = strtok($permission, " ");
                            if (!isset($allowedPermissions["all"]) || !in_array($permission, $allowedPermissions["all"])) {
                                $allowedPermissions["all"][] = $permission;
                            }
                        }
                    }
                    break;
                default:
                    break;
            }
        }
        return $allowedPermissions;
    }

    /**
     *
     * Authenticated user has particular permission for given access level over specific employee
     *
     */
    public function hasPermissionOverEmployee($permission, $employeeId, $roleId)
    {
        $perm = Permission::findByName($permission);
        $specificEmployees = $this->getFixedEmployeesList($roleId, $perm->id);
        return $specificEmployees->contains('employee_id', $employeeId);
    }

    /**
     * Add permission in allowedPermissions array
     */
    public function pushPermissionInAllowedPermissions($permission, $employeeId, $allowedPermissions)
    {
        if (strtok($permission, " ") == "request") {
            $allowedPermissions[$employeeId][$permission] = strtok($permission, " ");
        } else {
            $allowedPermissions[$employeeId][substr(strstr($permission, " "), 1)] = strtok($permission, " ");
        }
        return $allowedPermissions;
    }

    /**
     *
     * return access level name
     *
     */
    public function getAccessLevelLabel($givenAccessLevel)
    {
        if ($givenAccessLevel == DB::table('access_levels')->where('name', 'Self')->pluck('id')->first()) {
            return "self";
        } elseif ($givenAccessLevel == DB::table('access_levels')->where('name', 'Direct')->pluck('id')->first()) {
            return "direct";
        } elseif ($givenAccessLevel == DB::table('access_levels')->where('name', 'Direct and Indirect')->pluck('id')->first()) {
            return "directIndirect";
        } elseif ($givenAccessLevel == DB::table('access_levels')->where('name', 'Specific Employees')->pluck('id')->first()) {
            return "fixed";
        } elseif ($givenAccessLevel == DB::table('access_levels')->where('name', 'All Employees')->pluck('id')->first()) {
            return "all";
        }
    }

    /**
     *
     * get specific/fixed employees list
     *
     */
    public function getFixedEmployeesList($roleID, $permissionID)
    {
        return DB::Table('custom_role_permissions')->where('role_id', $roleID)->where('permission_id', $permissionID)->get();
    }

    /**
     *
     * check if given user is authenticated
     *
     */
    public function isAuthenticatedEmployee($employeeId)
    {
        return Auth::user()->id == $employeeId;
    }


    /**
     *
     * check if given employee is direct employee of authenticated user
     *
     */

    public function isDirectEmployee($employeeId)
    {
        $directEmployee = Auth::user()->directEmployees();
        return $directEmployee->contains('id', $employeeId);
    }

    /**
     *
     * check if given employee is direct or indirect employee of authenticated user
     *
     */
    public function isDirectOrIndirectEmployee($employeeId)
    {
        if ($this->isDirectEmployee($employeeId)) {
            return true;
        } else {
            $indirectEmployee = Auth::user()->indirectEmployees();
            return $indirectEmployee->contains('id', $employeeId);
        }
    }
    /**
     * get all roles of a user
     */

    public function getRoles($user)
    {
        $roles = $user->roles()->get();
        foreach ($roles as $role) {
            $rolesWithSubRoles[] = $role;
            //If user is not an "employee" he might have an employee level `sub_role` also
            if ($role->type != "employee" || $role->type == "admin") {
                if (isset($role->sub_role)) {
                    $rolesWithSubRoles[] = Role::where('id', $role->sub_role)->first();
                }
            }
        }
        return $rolesWithSubRoles;
    }
}
