<?php

namespace App\Domain\Employee\Models;

use App\Domain\ACL\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Note extends Model
{
    protected $fillable = [
        'username', 'note', 'employee_id'
    ];

    public function employee()
    {
        return $this->belongsTo('App\Domain\Employee\Models\Employee', 'employee_id', 'id');
    }
    //Custom method to get all default permissions related to Note Model
    public static function getDefaultPermissions($roleType, $notes)
    {
        $role = Role::where('name', $roleType)->first();
        $permissions = DB::table('role_permission_has_access_levels')
            ->where('role_id', $role->id)->join('permissions', 'role_permission_has_access_levels.permission_id', '=', 'permissions.id')->get();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'note note') !== false) {
                $notes['notes']['note_details']['note'][] = $permission->name;
            }
        }
        return $notes;
    }
    public static function getSelectedPermissions($roleType, $notes)
    {
        $role = Role::findById($roleType);
        $permissions = $role->permissions()->withPivot('role_id')->where('role_id', $role->id)->get();
        foreach ($permissions as $permission) {
            if (stripos($permission->name, 'note note') !== false) {
                $notes['notes']['note_details']['note']['checked'] = $permission->name;
            }
        }
        return $notes;
    }
}
