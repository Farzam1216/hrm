<?php

namespace App\Domain\Handbook\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domain\ACL\Models\Role;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function pages()
    {
        return $this->hasMany(Page::class, 'chapter_id');
    }

    //Get permissions of logged-in user related to Handbook.
    public static function getPermissionsWithAccessLevel($user)
    {
        $handbook = null;
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
                    if (stripos(strtolower($permission->name), 'manage company handbook') !== false) {
                        $handbook[$user->id][$permission->pivot->access_level_id][] = $permission->name;
                    }
                }
            }
        }
        return $handbook;
    }
}
