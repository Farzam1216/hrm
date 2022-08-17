<?php

namespace App\Domain\Benefit\Models;

use App\Domain\ACL\Models\Permission;
use App\Domain\ACL\Models\Role;
use Illuminate\Database\Eloquent\Model;

class BenefitPlan extends Model
{
    protected $fillable = [
        'name', 'start_date', 'plan_type_id', 'end_date', 'plan_cost_rate', 'description', 'plan_URL', 'reimbursement_amount',
        'reimbursement_frequency', 'reimbursement_currency'
    ];

    public function planType()
    {
        return $this->belongsTo(BenefitPlanType::class, 'plan_type_id');
    }

    public function planCoverages()
    {
        return $this->hasMany(BenefitPlanCoverage::class, 'plan_id');
    }

    public function benefitGroup()
    {
        return $this->belongsToMany(BenefitGroup::class, 'benefit_group_plans', 'plan_id', 'group_id');
    }
    public function benefitGroupPlan()
    {
        return $this->hasMany(BenefitGroupPlan::class, 'plan_id');
    }

    //Custom method to get all Benefit Plan Permissions. These permissions are only required for custom role
    public function getBenefitPlanPermissions($defaultPermissions)
    {
        $benefitPlans = self::all();
        $permissions = Permission::all();
        if ($benefitPlans->isNotEmpty()) {
            foreach ($permissions as $permission) {
                foreach ($benefitPlans as $benefitPlan) {
                    if (stripos($permission->name, 'benefitplan ' . $benefitPlan->id) !== false) {
                        $benefitPlanName = str_replace(' ', '_', $benefitPlan->name);
                        $defaultPermissions['benefits']['no_group'][$benefitPlan->id . '-' . $benefitPlanName][] = $permission->name;
                    }
                }
            }

            if (isset($defaultPermissions['benefits']['no_group'])) {
                $defaultPermissions = (new BenefitPlan())->sortArrayKeys($defaultPermissions);
            }
        }

        return $defaultPermissions;
    }

    //Change and sort Benefit Plans Label# to display on front-end
    public function sortArrayKeys($permissions)
    {
        $count = 1;
        foreach ($permissions['benefits']['no_group'] as $benefitPlanLabel => $benefitPlanPermissions) {
            if ($benefitPlanLabel != "benefit_history" && $benefitPlanLabel != "benefit_group") {
                // returns beginning index of '-'
                $end_index = strpos($benefitPlanLabel, "-");

                $sortedbenefitPlanLabel = substr_replace($benefitPlanLabel, $count, 0, $end_index);
                $permissions['benefits']['no_group'][$sortedbenefitPlanLabel] = $permissions['benefits']['no_group'][$benefitPlanLabel];
                if ($sortedbenefitPlanLabel != $benefitPlanLabel) {
                    unset($permissions['benefits']['no_group'][$benefitPlanLabel]);
                }
                $count++;
            }
        }
        return $permissions;
    }

    //Get permissions of logged-in user related to Employee Visa Model.
    public static function getPermissionsWithAccessLevel($user)
    {
        $benefitPlan = null;
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
                    if (stripos($permission->name, ' benefitplan ') !== false) {
                        $benefitPlan[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                }
            }
        }

        return $benefitPlan;
    }
}
