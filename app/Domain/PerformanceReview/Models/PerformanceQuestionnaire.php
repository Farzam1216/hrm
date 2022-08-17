<?php

namespace App\Domain\PerformanceReview\Models;

use DB;
use App\Domain\ACL\Models\Role;
use Illuminate\Database\Eloquent\Model;
use App\Domain\Employee\Models\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PerformanceQuestionnaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'submitter_id', 'status', 'comment', 'decision_authority_id', 'employee_can_view',
    ];

    public function answers()
    {
        return $this->hasMany(PerformanceQuestionnaireAnswer::class, 'questionnaire_id');
    }

    public function submitters()
    {
        return $this->belongsTo(Employee::class, 'submitter_id', 'id');
    }

    public function decision_authority()
    {
        return $this->belongsTo(Employee::class, 'decision_authority_id', 'id');
    }

    public function employees()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function decision()
    {
        return $this->belongsTo(Employee::class, 'decision_authority_id', 'id');
    }

    //Custom method to get all default permissions related to Performance Questionnaire Model
    public static function getDefaultPermissions($roleType, $performan)
    {
        $role = Role::where('name', $roleType)->first();
        $permissions = DB::table('role_permission_has_access_levels')
            ->where('role_id', $role->id)->join('permissions', 'role_permission_has_access_levels.permission_id', '=', 'permissions.id')->get();
        foreach ($permissions as $permission) {
            if ($permission->name == 'manage performance review') {
                $performan['performance']['checkbox']['performance'][] = $permission->name;
            }
        }
        return $performan;
    }

    public static function getSelectedPermissions($roleType, $performance)
    {
        $role = Role::findById($roleType);
        $permissions = $role->permissions()->withPivot('role_id')->where('role_id', $role->id)->get();
        foreach ($permissions as $permission) {
            if ($permission->name == 'manage performance review') {
                $performance['performance']['checkbox']['performance']['checked'] = $permission->name;
            }
        }
        return $performance;
    }

    //Get permissions of logged-in user related to Handbook.
    public static function getPermissionsWithAccessLevel($user)
    {
        $performance = null;
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
                    if (stripos(strtolower($permission->name), 'manage performance review') !== false) {
                        $performance[$user->id]['all'][] = $permission->name;
                    } elseif (stripos(strtolower($permission->name), 'manage performance review assign') !== false) {
                        $performance[$user->id]['all'][] = $permission->name;
                    } elseif (stripos(strtolower($permission->name), 'manage performance review decision') !== false) {
                        $performance[$user->id]['all'][] = $permission->name;
                    }
                }
            }
        }
        
        return $performance;
    }
}
