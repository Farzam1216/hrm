<?php

namespace App\Domain\Employee\Models;

use App\Domain\ACL\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmployeeDocument extends Model
{
    protected $fillable = [
        'employee_id', 'doc_name', 'doc_file', 'doc_type', 'status'
    ];

    /**
     *  Get default prmissions for current role     *
     * @param int $roleType
     * @param array $document
     * @return array $document
     */
    public static function getDefaultPermissions($roleType, $document)
    {
        $role = Role::where('name', $roleType)->first();
        $permissions = DB::table('role_permission_has_access_levels')
            ->where('role_id', $role->id)->join('permissions', 'role_permission_has_access_levels.permission_id', '=', 'permissions.id')->get();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'employeedocument doc_name') !== false) {
                $document['document']['no_group']['employee_document'][] = $permission->name;
            }
        }
        return $document;
    }
    /**
     *  Get permissions of logged-in user related to Employee Document Model.
     *
     * @param $user
     *
     * @return mixed|null $document
     */
    public static function getPermissionsWithAccessLevel($user)
    {
        $document = null;
        $roles = $user->roles()->get();
        foreach ($roles as $role) {
            $roleWithSubRole[] = $role;
            //If user is not an "employee" he might have a sub_role (employee) also
            if ($role->type != 'employee') {
                if (isset($role->sub_role)) {
                    $roleWithSubRole[] = Role::where('id', $role->sub_role)->first();
                }
            }
            foreach ($roleWithSubRole as $role) {
                $permissions = $role->permissions()->get();
                foreach ($permissions as $permission) {
                    if (stripos($permission->name, 'employeedocument doc_name') !== false) {
                        $document[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                }
            }
        }

        return $document;
    }
    /**
     *  Get permissions which are selected in current role
     *
     * @param int $roleType
     * @param array $document
     * @return array $document
     */
    public static function getSelectedPermissions($roleType, $document)
    {
        $role = Role::findById($roleType);
        $permissions = $role->permissions()->withPivot('role_id')->where('role_id', $role->id)->get();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'employeedocument doc_name') !== false) {
                $document['document']['no_group']['employee_document']['checked'] = $permission->name;
            }
        }

        return $document;
    }
}
