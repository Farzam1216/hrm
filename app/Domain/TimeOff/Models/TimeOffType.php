<?php

namespace App\Domain\TimeOff\Models;

use App\Domain\ACL\Models\Permission;
use App\Domain\ACL\Models\Role;
use Cassandra\Time;
use Illuminate\Database\Eloquent\Model;

class TimeOffType extends Model
{
    protected $fillable = [
        'time_off_type_name',
    ];

    public function timeoff()
    {
        return $this->hasMany('App\Domain\TimeOff\Models\Policy', 'time_off_type');
    }
    public function assignTimeOff()
    {
        return $this->hasMany('App\Domain\TimeOff\Models\AssignTimeOffType', 'type_id');
    }
    //Custom method to get all default permissions related to Time Off Type Model
    public static function getDefaultPermissions($roleType, $timeOffType)
    {
        $timeOffType = (new TimeOffType())->getTimeOffTypesPermissions($roleType, $timeOffType);
        return $timeOffType;
    }

    //Custom method to get all Time Off Types Permissions
    public function getTimeOffTypesPermissions($roleType, $timeOffTypePermissions)
    {

        $timeoffTypes = self::all();
        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'request timeofftype all') !== false) {
                if (!isset($timeOffTypePermissions['time_off']['checkbox']['can_request_time_off']['1-All_current_and_future_types'][0])) {
                    $timeOffTypePermissions['time_off']['checkbox']['can_request_time_off']['1-All_current_and_future_types'][] = $permission->name;
                }
            }
            if ($timeoffTypes->isNotEmpty()) {
                if ($roleType != "employee" && stripos($permission->name, 'request timeofftype') !== false) {
                    continue;
                }
                if ($roleType != "custom" && stripos($permission->name, 'edit timeofftype') !== false) {
                    continue;
                }
                foreach ($timeoffTypes as $timeOffType) {
                    $timeOffTypeName = str_replace(' ', '_', $timeOffType->time_off_type_name);
                    if (stripos($permission->name, 'request timeofftype all') !== false) {
                        continue;
                    } elseif (stripos($permission->name, 'request timeofftype ' . $timeOffType->id) !== false) {
                        $timeOffTypePermissions['time_off']['checkbox']['can_request_time_off'][$timeOffType->id . '-' . $timeOffTypeName][] = $permission->name;
                    } elseif (stripos($permission->name, 'timeofftype ' . $timeOffType->id) !== false) {
                        $timeOffTypePermissions['time_off']['active_time_off_types'][$timeOffType->id . '-' . $timeOffTypeName][] = $permission->name;
                    }
                }
            }
        
            if ($permission->name == 'manage request time off decision') {
                $timeOffTypePermissions['time_off']['checkbox']['time_off_decision'][] = $permission->name;
            }
        }

        if (isset($timeOffTypePermissions['time_off']['checkbox']['can_request_time_off'])) {
            $timeOffTypePermissions['time_off']['checkbox']['can_request_time_off'] = (new TimeOffType())->sortArrayKeys($timeOffTypePermissions['time_off']['checkbox']['can_request_time_off']);
        }
        if (isset($timeOffTypePermissions['time_off']['active_time_off_types'])) {
            $timeOffTypePermissions['time_off']['active_time_off_types'] = (new TimeOffType())->sortArrayKeys($timeOffTypePermissions['time_off']['active_time_off_types']);
        }
        return $timeOffTypePermissions;
    }

    //Change and sort Time Off Types Label# to display on front-end
    public function sortArrayKeys($permissions)
    {
        $count = 1;
        foreach ($permissions as $timeOffTypeLabel => $timeOffTypePermissions) {
            // returns beginning index of '-'
            $end_index = strpos($timeOffTypeLabel, "-");

            $sortedTimeOffTypeLabel = substr_replace($timeOffTypeLabel, $count, 0, $end_index);
            $permissions[$sortedTimeOffTypeLabel] = $permissions[$timeOffTypeLabel];
            if ($sortedTimeOffTypeLabel != $timeOffTypeLabel) {
                unset($permissions[$timeOffTypeLabel]);
            }
            $count++;
        }
        return $permissions;
    }

    //Get permissions of logged-in user related to Employee Visa Model.
    public static function getPermissionsWithAccessLevel($user)
    {
        $timeOffType = null;
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
                    if (stripos($permission->name, 'request timeofftype ') !== false) {
                        $timeOffType[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    } elseif (stripos($permission->name, ' timeofftype ') !== false) {
                        $timeOffType[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    } elseif (stripos($permission->name, 'time off decision') !== false) {
                        $timeOffType[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                    //Non-field permissions
                    /*I'm accessing this permission in this model because everything else related to Time Off (Request Time Off,
                    Change policy, Adjust Balance) depends on Time Off Types. If no type is assigned to a user, all these permissions
                    are meaningless. A user with this permission can record time off, change policy  and adjust balance for all employees
                    */
                    if (stripos($permission->name, 'manage employees PTO') !== false) {
                        $timeOffType[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                }
            }
        }
        return $timeOffType;
    }
    public static function getSelectedPermissions($roleType, $timeOffTypePermissions)
    {
        $timeoffTypes = self::all();
        $role = Role::findById($roleType);
        $permissions = $role->permissions()->withPivot('role_id')->where('role_id', $role->id)->get();
        if ($timeoffTypes->isNotEmpty()) {
            foreach ($permissions as $permission) {
                if ($role->type != "employee" && stripos($permission->name, 'request timeofftype') !== false) {
                    continue;
                }
                if ($role->type != "custom" && stripos($permission->name, 'edit timeofftype') !== false) {
                    continue;
                }
                foreach ($timeoffTypes as $timeOffType) {
                    $timeOffTypeName = str_replace(' ', '_', $timeOffType->time_off_type_name);
                    if (stripos($permission->name, 'request timeofftype all') !== false) {
                        if (!isset($timeOffTypePermissions['time_off']['checkbox']['can_request_time_off'][0])) {
                            $timeOffTypePermissions['time_off']['checkbox']['can_request_time_off']['1-All_current_and_future_types']['checked'] = $permission->name;
                        }
                    } elseif (stripos($permission->name, 'request timeofftype ' . $timeOffType->id) !== false) {
                        $timeOffTypePermissions['time_off']['checkbox']['can_request_time_off'][$timeOffType->id . '-' . $timeOffTypeName]['checked'] = $permission->name;
                    } elseif (stripos($permission->name, 'timeofftype ' . $timeOffType->id) !== false) {
                        $timeOffTypePermissions['time_off']['active_time_off_types'][$timeOffType->id . '-' . $timeOffTypeName]['checked'] = $permission->name;
                    }
                }
            }

            if (isset($timeOffTypePermissions['time_off']['checkbox']['can_request_time_off'])) {
                $timeOffTypePermissions['time_off']['checkbox']['can_request_time_off'] = (new TimeOffType())->sortArrayKeys($timeOffTypePermissions['time_off']['checkbox']['can_request_time_off']);
            }
            if (isset($timeOffTypePermissions['time_off']['active_time_off_types'])) {
                $timeOffTypePermissions['time_off']['active_time_off_types'] = (new TimeOffType())->sortArrayKeys($timeOffTypePermissions['time_off']['active_time_off_types']);
            }
        }
        
        foreach ($permissions as $permission) {
            if ($permission->name == 'manage request time off decision') {
                $timeOffTypePermissions['time_off']['checkbox']['time_off_decision']['checked'] = $permission->name;
            }
        }

        if (isset($timeOffTypePermissions['time_off']) && $role->type == 'employee') {
            $timeOffTypePermissions['time_off']['can_request_time_off_checked'] = true;
        }
        return $timeOffTypePermissions;
    }
    public static function sortPermissionKeys(&$defaultPermissions)
    {
        if (isset($defaultPermissions['time_off']['checkbox'],
        $defaultPermissions['time_off']['active_time_off_types'],
        $defaultPermissions['time_off']['time_off_policies'])) {
            $timeOff = $defaultPermissions['time_off'];
            $sortedPermissions = [];
            !array_key_exists('checkbox', $timeOff) ?: $sortedPermissions['time_off']['checkbox'] = $timeOff['checkbox'];
            !array_key_exists('active_time_off_types', $timeOff) ?: $sortedPermissions['time_off']['active_time_off_types'] = $timeOff['active_time_off_types'];
            !array_key_exists('time_off_policies', $timeOff) ?: $sortedPermissions['time_off']['time_off_policies'] = $timeOff['time_off_policies'];
            $canrequest=null;
            if(isset($defaultPermissions['time_off']['can_request_time_off_checked']))
            {
                $canrequest=$defaultPermissions['time_off']['can_request_time_off_checked'];
            }
            $defaultPermissions['time_off']=$sortedPermissions['time_off'];
            if(isset($canrequest))
            {
                $defaultPermissions['time_off']['can_request_time_off_checked']=true;
            }
        }
        return $defaultPermissions;
    }
}
