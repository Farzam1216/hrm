<?php

namespace App\Domain\Attendance\Models;

use App\Domain\Employee\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use App\Domain\ACL\Models\Role;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeAttendance extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'time_in',
        'time_out',
        'time_in_status',
        'attendance_status',
        'reason_for_leaving',
        'employee_id',
    ];

    protected $dates = [ 'deleted_at' ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function comments()
    {
        return $this->hasMany(EmployeeAttendanceComments::class, 'employee_attendance_id');
    }

    public function attendanceHistory()
    {
        return $this->hasMany(EmployeeAttendanceHistory::class, 'attendance_id');
    }

    //Get permissions of logged-in user related to Attendance.
    public static function getPermissionsWithAccessLevel($user)
    {
        $attendance = null;
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
                    if (stripos(strtolower($permission->name), 'employee_attendance') !== false) {
                        $attendance[$user->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                }
            }
        }
        return $attendance;
    }
}
