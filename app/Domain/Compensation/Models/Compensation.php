<?php

namespace App\Domain\Compensation\Models;

use App\Domain\ACL\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Domain\Employee\Models\Employee;
use App\Domain\Compensation\Models\CompensationChangeReason;
use App\Domain\PayRoll\Models\PaySchedule;

class Compensation extends Model
{
    use HasFactory;

    protected $fillable = [
        'effective_date', 'pay_schedule_id', 'pay_type', 'pay_rate', 'pay_rate_frequency', 'overtime_status', 'change_reason_id', 'comment', 'status', 'employee_id',
    ];

    public function changeReason()
    {
        return $this->belongsTo(CompensationChangeReason::class,'change_reason_id', 'id');
    }

    public function paySchedule()
    {
        return $this->belongsTo(PaySchedule::class,'pay_schedule_id', 'id');
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
                    if (stripos($permission->name, 'manage setting compensation') !== false) {
                        $employee[$user->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                }
            }
        }

        return $employee;
    }
}
