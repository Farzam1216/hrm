<?php

namespace App\Domain\Holiday\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domain\Employee\Models\EmployeeHoliday;
use App\Domain\Employee\Models\Employee;
use App\Domain\ACL\Models\Role;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'date', 'pay_rate', 'assigned_to'
    ];

    public function employee_holidays()
    {
        return $this->hasMany(EmployeeHoliday::class, 'holiday_id');
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
                    if (stripos($permission->name, 'manage company holidays') !== false) {
                        $employee[$user->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                }
            }
        }

        return $employee;
    }
}
