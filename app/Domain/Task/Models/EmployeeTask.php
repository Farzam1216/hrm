<?php

namespace App\Domain\Task\Models;

use App\Domain\ACL\Models\Role;
use Illuminate\Database\Eloquent\Model;

class EmployeeTask extends Model
{    //Copy task template
    protected $fillable = [
        'employee_id',
        'task_id',
        'name',
        'description',
        'category_name',
        'type',
        'calculated_due_date',
        'assigned_by',
        'assigned_to',
        'completed_at',
        'completed_by',
    ];

    public function Task()
    {
        return $this->belongsTo('App\Domain\Task\Models\Task', 'task_id');
    }

    public function assignedby()
    {
        return $this->belongsTo('App\Domain\Employee\Models\Employee', 'assigned_by');
    }

    public function assignedto()
    {
        return $this->belongsTo('App\Domain\Employee\Models\Employee', 'assigned_to');
    }

    public function assignedfor()
    {
        return $this->belongsTo('App\Domain\Employee\Models\Employee', 'assigned_for');
    }

    /**
     *  Get permissions of logged-in user related to Employee Task Tab.
     *
     * @param $user
     *
     * @return mixed|null $tasks
     */
    public static function getPermissionsWithAccessLevel($user)
    {
        $tasks = null;
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
                    if (stripos($permission->name, 'onboarding tab') !== false) {
                        $tasks[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                    if (stripos($permission->name, 'offboarding tab') !== false) {
                        $tasks[$role->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                }
            }
        }

        return $tasks;
    }
}
