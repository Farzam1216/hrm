<?php

namespace App\Domain\Task\Models;

use App\Domain\ACL\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Task extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name','description',
        'assigned_to',
        'category',
        'type',
        'due_date',
        'period',
        'assigned_for_all_employees'
    ];

    public function taskCategory()
    {
        return $this->belongsTo('App\Domain\Task\Models\TaskCategory', 'category');
    }
    public function taskRequiredForFilters()
    {
        return $this->hasMany('App\Domain\Task\Models\TaskRequiredForFilter', 'task_id');
    }
    public function taskDocuments()
    {
        return $this->hasMany(TaskAttachmentTemplate::class, 'task_id');
    }

    /**
     *  Get permissions which are selected in current role
     *
     * @param int $roleType
     * @param array $document
     * @return array $document
     */
    public static function getDefaultPermissions($roleType, $tasks)
    {
        $role = Role::where('name', $roleType)->first();
        $permissions = DB::table('role_permission_has_access_levels')
            ->where('role_id', $role->id)->join('permissions', 'role_permission_has_access_levels.permission_id', '=', 'permissions.id')->get();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'onboarding tab') !== false) {
                $tasks['Onboarding']['no_group']['onboarding_tab'][] = $permission->name;
            }
            if (stripos($permission->name, 'offboarding tab') !== false) {
                $tasks['Offboarding']['no_group']['offboarding_tab'][] = $permission->name;
            }
        }
        return $tasks;
    }
    /**
     *  Get permissions which are selected in current role
     *
     * @param int $roleType
     * @param array $document
     * @return array $document
     */
    public static function getSelectedPermissions($roleType, $tasks)
    {
        $role = Role::findById($roleType);
        $permissions = $role->permissions()->withPivot('role_id')->where('role_id', $role->id)->get();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'onboarding tab') !== false) {
                $tasks['Onboarding']['no_group']['onboarding_tab']['checked'] = $permission->name;
            }
            if (stripos($permission->name, 'offboarding tab') !== false) {
                $tasks['Offboarding']['no_group']['offboarding_tab']['checked'] = $permission->name;
            }
        }
        return $tasks;
    }
}
