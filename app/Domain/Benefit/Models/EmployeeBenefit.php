<?php

namespace App\Domain\Benefit\Models;

use App\Domain\ACL\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmployeeBenefit extends Model
{
    protected $fillable = [
        'benefit_group_plan_id', 'employee_id', 'employee_benefit_plan_coverage',
        'deduction_frequency', 'employee_payment', 'company_payment',
    ];

    public function benefitGroupPlan()
    {
        return $this->belongsTo(BenefitGroupPlan::class, 'benefit_group_plan_id');
    }

    public function employeeBenefitStatuses()
    {
        return $this->hasMany(EmployeeBenefitStatus::class, 'employee_benefit_id')->orderBy('updated_at');
    }
    public function employeeBenefitStatusHistories()
    {
        return $this->hasMany(EmployeeBenefitStatusHistory::class, 'employee_benefit_id')->orderBy('created_at');
    }

    //Custom method to get all default permissions related to Employee Benefits Model
    public static function getDefaultPermissions($roleType, $employeeBenefit)
    {
        $role = Role::where('type', $roleType)->first();
        $permissions = DB::table('role_permission_has_access_levels')
            ->where('role_id', $role->id)->join('permissions', 'role_permission_has_access_levels.permission_id', '=', 'permissions.id')->get();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'employeebenefit company_payment') !== false) {
                $employeeBenefit['benefits']['checkbox']['Show what the company pays for each benefit'][] = $permission->name;
            }
            if (stripos($permission->name, 'employee benefits history') !== false) {
                $employeeBenefit['benefits']['no_group']['benefit_history'][] = $permission->name;
            }
        }

        return $employeeBenefit;
    }

    //Get permissions of logged-in user related to Employee Visa Model.
    public static function getPermissionsWithAccessLevel($user)
    {
        $employeeBenefit = null;
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
                    if (stripos($permission->name, 'employeebenefit company_payment') !== false) {
                        $employeeBenefit[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                    if (stripos($permission->name, 'employee benefits history') !== false) {
                        $employeeBenefit[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                }
            }
        }

        return $employeeBenefit;
    }

    public static function getSelectedPermissions($roleType, $employeeBenefit)
    {
        $role = Role::findById($roleType);
        $permissions = $role->permissions()->withPivot('role_id')->where('role_id', $role->id)->get();
        foreach ($permissions as $permission) {
            if ($role->type == 'employee') {
                if (stripos($permission->name, 'employeebenefit company_payment') !== false) {
                    $employeeBenefit['benefits']['checkbox']['Show what the company pays for each benefit']['checked'] = $permission->name;
                }
            }
            if (stripos($permission->name, 'employee benefits history') !== false) {
                $employeeBenefit['benefits']['no_group']['benefit_history']['checked'] = $permission->name;
            }
        }

        return $employeeBenefit;
    }

    public static function sortPermissionKeys(&$defaultPermissions)
    {
        if (isset($defaultPermissions['benefits'])) {
            $benefits = $defaultPermissions['benefits'];
            $sortedPermissions = [];
            $companyPayment = 'Show what the company pays for each benefit';
            !array_key_exists($companyPayment, $benefits['no_group']) ?: $sortedPermissions['benefits']['no_group'][$companyPayment] = $benefits['checkbox'][$companyPayment];
            !array_key_exists('benefit_group', $benefits['no_group']) ?: $sortedPermissions['benefits']['no_group']['benefit_group'] = $benefits['no_group']['benefit_group'];
            !array_key_exists('benefit_history', $benefits['no_group']) ?: $sortedPermissions['benefits']['no_group']['benefit_history'] = $benefits['no_group']['benefit_history'];
            //to sort the position of indexes in defaultPermissions array
            $dependents=$defaultPermissions['benefits']['dependents'];
            $defaultPermissions['benefits'] = $sortedPermissions['benefits'];
            $defaultPermissions['benefits']['dependents'] = $dependents;
        }
        return $defaultPermissions;
    }
}
