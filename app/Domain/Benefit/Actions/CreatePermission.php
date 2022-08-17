<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\ACL\Models\Permission;

class CreatePermission
{
    /**
     * create benefit plan permission on new benefit plan create
     * @param $permission
     * @return
     */
    public function execute($permission)
    {
        return Permission::firstOrCreate(['name' => $permission]);
    }
}
