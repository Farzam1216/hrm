<?php

namespace App\Domain\Benefit\Models;

use App\Domain\ACL\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BenefitGroup extends Model
{
    protected $fillable = [
        'name',
        'payperiod'
    ];

    public function benefitGroupEmployee()
    {
        return $this->hasMany(BenefitGroupEmployee::class, 'benefit_group_id');
    }

    public function benefitPlan()
    {
        return $this->belongsToMany(BenefitPlan::class, 'benefit_group_plans', 'group_id', 'plan_id')
            ->as('groupPlan')->withPivot(
                'id',
                'eligibility',
                'wait_period',
                'type_of_period'
            )->withTimestamps();
    }
    public function groupPlans()
    {
        return $this->hasMany(BenefitGroupPlan::class, 'group_id');
    }
    //Custom method to get all default permissions related to Benefit Group Model
    public static function getDefaultPermissions($roleType, $benefitGroup)
    {
        $role = Role::where('type', $roleType)->first();
        $permissions = DB::table('role_permission_has_access_levels')
            ->where('role_id', $role->id)->join('permissions', 'role_permission_has_access_levels.permission_id', '=', 'permissions.id')->get();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'benefitgroup name') !== false) {
                $benefitGroup['benefits']['no_group']['benefit_group'][] = $permission->name;
            }
        }
        return $benefitGroup;
    }

    //Get permissions of logged-in user related to Employee Visa Model.
    public static function getPermissionsWithAccessLevel($user)
    {
        $benefitGroup = null;
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
                    if (stripos($permission->name, 'benefitgroup name') !== false) {
                        $benefitGroup[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                }
            }
        }
        return $benefitGroup;
    }

    public static function getSelectedPermissions($roleType, $benefitGroup)
    {
        $role = Role::findById($roleType);
        $permissions = $role->permissions()->withPivot('role_id')->where('role_id', $role->id)->get();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'benefitgroup name') !== false) {
                $benefitGroup['benefits']['no_group']['benefit_group']['checked'] = $permission->name;
            }
        }
        return $benefitGroup;
    }
}
