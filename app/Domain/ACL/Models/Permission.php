<?php

namespace App\Domain\ACL\Models;

use illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Permission as BasePermissionModel;

class Permission extends BasePermissionModel
{
    /**
     * A permission can be applied to roles.
     * Overriding default roles method from Spatie\Permission\Models\Permission model
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.role'),
            config('permission.table_names.role_permission_has_access_levels'),
            'permission_id',
            'role_id'
        )->withPivot('access_level_id');
    }
}
