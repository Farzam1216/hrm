<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\ACL\Models\Permission;

class DeletePermission
{
    /**
     * delete benefit plan permission with the removal of benefit plan
     * @param $permission
     * @return
     */
    public function execute($permission)
    {
        return Permission::where('name', $permission)->delete();
    }
}
