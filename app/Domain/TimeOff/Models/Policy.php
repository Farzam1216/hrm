<?php

namespace App\Domain\TimeOff\Models;

use App\Domain\ACL\Models\Permission;
use App\Domain\ACL\Models\Role;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    protected $fillable = [
        'policy_name',
        'policy_type',
        'first_accrual',
        'carry_over_date',
        'accrual_happen',
        'accrual_transition_happend',
        'time_off_type',
    ];
    public function timeoff()
    {
        return $this->belongsTo('App\Domain\TimeOff\Models\TimeOffType', 'time_off_type');
    }
    public function level()
    {
        return $this->hasMany('App\Domain\TimeOff\Models\PolicyLevel', 'policy_id');
    }
    //Custom method to get all default permissions related to Policy Model
    public static function getDefaultPermissions($roleType, $policy)
    {
        $policy = (new Policy())->getTimeOffPoliciesPermissions($roleType, $policy);
        return $policy;
    }

    //Custom method to get all Time Off Policies Permissions
    public function getTimeOffPoliciesPermissions($roleType, $timeOffPolicy)
    {
        $timeoffPolicies = self::all();
        $permissions = Permission::all();
        if ($timeoffPolicies->isNotEmpty()) {
            foreach ($permissions as $permission) {
                foreach ($timeoffPolicies as $key => $timeoffPolicy) {
                    if ($timeoffPolicy->policy_name !== "None" && $timeoffPolicy->policy_name != "Manual Updated Balance") {
                        //Employee and Manager don't contain edit permissions
                        if ($roleType != "custom" && stripos($permission->name, 'edit policy ' . $timeoffPolicy->id) !== false) {
                            continue;
                        }
                        if (stripos($permission->name, 'policy ' . $timeoffPolicy->id) !== false) {
                            $policyName = str_replace(' ', '_', $timeoffPolicy->policy_name);
                            $timeOffPolicy['time_off']['time_off_policies'][$timeoffPolicy->id . '-' . $policyName][] = $permission->name;
                        }
                    }
                }
            }
            if (isset($timeOffPolicy['time_off']['time_off_policies'])) {
                $timeOffPolicy = (new Policy())->sortArrayKeys($timeOffPolicy);
            }
        }

        return $timeOffPolicy;
    }

    //Change and sort Time Off Policies Label# to display on front-end
    public function sortArrayKeys($permissions)
    {
        $count = 1;
        foreach ($permissions['time_off']['time_off_policies'] as $timeOffPolicyLabel => $timeOffPolicyPermissions) {
            // returns beginning index of '-'
            $end_index = strpos($timeOffPolicyLabel, "-");

            $sortedTimeOffPolicyLabel = substr_replace($timeOffPolicyLabel, $count, 0, $end_index);
            $permissions['time_off']['time_off_policies'][$sortedTimeOffPolicyLabel] = $permissions['time_off']['time_off_policies'][$timeOffPolicyLabel];
            if ($sortedTimeOffPolicyLabel != $timeOffPolicyLabel) {
                unset($permissions['time_off']['time_off_policies'][$timeOffPolicyLabel]);
            }
            $count++;
        }
        return $permissions;
    }
    //Get permissions of logged-in user related to Policy Model.
    public static function getPermissionsWithAccessLevel($user)
    {
        $policy = null;
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
                    if (stripos($permission->name, ' policy ') !== false) {
                        $policy[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                }
            }
        }
        return $policy;
    }
    public static function getSelectedPermissions($roleType, $timeOffPolicy)
    {
        $timeoffPolicies = self::all();
        $role = Role::findById($roleType);
        $permissions = $role->permissions()->withPivot('role_id')->where('role_id', $role->id)->get();
        //
        if ($timeoffPolicies->isNotEmpty()) {
            foreach ($permissions as $permission) {
                foreach ($timeoffPolicies as $key => $timeoffPolicy) {
                    if ($timeoffPolicy->policy_name !== "None" && $timeoffPolicy->policy_name != "Manual Updated Balance") {
                        //Employee and Manager don't contain edit permissions
                        if ($roleType != "custom" && stripos($permission->name, 'edit policy ' . $timeoffPolicy->id) !== false) {
                            continue;
                        }
                        if (stripos($permission->name, 'policy ' . $timeoffPolicy->id) !== false) {
                            $policyName = str_replace(' ', '_', $timeoffPolicy->policy_name);
                            $timeOffPolicy['time_off']['time_off_policies'][$timeoffPolicy->id . '-' . $policyName]['checked'] = $permission->name;
                        }
                    }
                }
            }
            if (isset($timeOffPolicy['time_off']['time_off_policies'])) {
                $timeOffPolicy = (new Policy())->sortArrayKeys($timeOffPolicy);
            }
        }
        return $timeOffPolicy;
    }
}
