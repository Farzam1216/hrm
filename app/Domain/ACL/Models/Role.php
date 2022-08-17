<?php

namespace App\Domain\ACL\Models;

use illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Contracts\Role as RoleContract;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Guard;
use Spatie\Permission\Models\Role as BaseRoleModel;

class Role extends BaseRoleModel
{
    /**
     * A role may have various permission levels for various permissions.
     * Overriding default permissions method from Spatie\Permission\Models\Role model
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.permission'),
            config('permission.table_names.role_permission_has_access_levels'),
            'role_id',
            'permission_id'
        )->withPivot('access_level_id');
    }

    /**
     * Grant the given permission(s) to a role.
     *Override existing 'givePermissionTo' method from 'HasPermission' trait.
     * @param null $accessInfo
     * TODO:: (Can be improved) $accessInfo format => $accessInfo['access-level'] and $accessInfo['employees']
     * @param string|array|\Spatie\Permission\Contracts\Permission|\Illuminate\Support\Collection $permissions
     *
     * @return $this
     */
    public function givePermissionTo($accessInfo = null, ...$permissions)
    {
        $permissions = collect($permissions)
            ->flatten()
            ->map(function ($permission) {
                if (empty($permission)) {
                    return false;
                }
                return $this->getStoredPermission($permission);
            })
            ->filter(function ($permission) {
                return $permission instanceof Permission;
            })
            ->each(function ($permission) {
                $this->ensureModelSharesGuard($permission);
            })
            ->map->id
            ->all();
        if (isset($accessInfo['access-level'])) {
            $accessLevel = array_fill(0, count($permissions), ['access_level_id' => $accessInfo['access-level']]);
            $permissions  = array_combine($permissions, $accessLevel);
        }

        $model = $this->getModel();

        if ($model->exists) {
            $this->permissions()->sync($permissions, false);
            $model->load('permissions');
        } else {
            $class = \get_class($model);

            $class::saved(
                function ($object) use ($permissions, $model) {
                    static $modelLastFiredOn;
                    if ($modelLastFiredOn !== null && $modelLastFiredOn === $model) {
                        return;
                    }
                    $object->permissions()->sync($permissions, false);
                    $object->load('permissions');
                    $modelLastFiredOn = $object;
                }
            );
        }
        //If Access Level is for Specific/Some Employees then insert data into "custom_role_permissions" table as well
        if (isset($accessInfo['access-level']) && $accessInfo['access-level'] == 3 && !empty($accessInfo['employees'])) {
            $this->addEmployees($this, $permissions, $accessInfo['employees']);
        }

        $this->forgetCachedPermissions();

        return $this;
    }

    /**
     * Add specific employees IDs in custom_role_permissions table
     * whose information is accessible for custom role
     * @param $role
     * @param $permissions
     * @param $employees
     */
    private function addEmployees($role, $permissions, $employees)
    {
        foreach ($permissions as $index => $permissionID) {
            if (is_array($permissionID)) {
                $permissionID = $index;
            }
            foreach ($employees as $employeeID) {
                DB::table(config('permission.table_names.custom_role_permissions'))->updateOrInsert(
                    ['role_id' => $role->id, 'permission_id' => $permissionID, 'employee_id' => $employeeID],
                    ['role_id' => $role->id, 'permission_id' => $permissionID, 'employee_id' => $employeeID]
                );
            }
        }
        return;
    }

    /**
     * Find a role by its name and guard name.
     *
     * @param string $type
     * @param string|null $guardName
     *
     * @return RoleContract|BaseRoleModel
     *
     */
    public static function findByType(string $type, $guardName = null): RoleContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $role = static::where('type', $type)->where('guard_name', $guardName)->first();

        if (!$role) {
            throw RoleDoesNotExist::named($type);
        }

        return $role;
    }
    /**
     * Remove all current permissions and set the given ones.
     *
     * @param string|array|\Spatie\Permission\Contracts\Permission|\Illuminate\Support\Collection $permissions
     *
     * @return $this
     */
    public function syncPermissions(...$permissions)
    {
        $this->permissions()->detach();

        return $this->givePermissionTo($permissions);
    }

    /**
     *  Get permissions of logged-in user (if not admin) related to manage Employee Access Levels.
     *
     * @param $user
     *
     * @return mixed|null
     */
    public static function getPermissionsWithAccessLevel($user)
    {
        $employeeAccessLevel = null;
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
                    //Non-field permissions
                    if (stripos($permission->name, 'manage setting employee_accesslevel') !== false) {
                        $employeeAccessLevel[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                }
            }
        }

        return $employeeAccessLevel;
    }
}
