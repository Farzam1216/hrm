<?php

namespace App\Domain\PayRoll\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domain\PayRoll\Models\PayScheduleAssign;
use App\Domain\PayRoll\Models\PayScheduleDate;
use App\Domain\ACL\Models\Role;

class PaySchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'frequency', 'period_ends', 'pay_days', 'exceptional_pay_day',
    ];

    public function payScheduleDates()
    {
        return $this->hasMany(PayScheduleDate::class, 'pay_schedule_id');
    }

    public function assignedEmployees()
    {
        return $this->hasMany(PayScheduleAssign::class, 'pay_schedule_id');
    }

    /**
     *  Get permissions of logged-in user related to Employee Model.
     *
     * @param $user
     *
     * @return mixed|null $employee
     */
    public static function getPermissionsWithAccessLevel($user)
    {
        $employee = null;
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
                    if (stripos($permission->name, 'manage pay schedule') !== false) {
                        $employee[$user->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                }
            }
        }

        return $employee;
    }
}
