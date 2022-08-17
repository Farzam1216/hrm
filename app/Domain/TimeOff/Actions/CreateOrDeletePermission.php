<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\ACL\Models\Permission;

class CreateOrDeletePermission
{
    /**
     * @param $permission
     * @return mixed
     */
    public function execute($createOrDeletePermission, $permission)
    {
        if ($createOrDeletePermission == "createPermission") {
            return Permission::firstOrCreate(['name' => $permission]);
        } elseif ($createOrDeletePermission == "deletePermission") {
            return Permission::where('name', $permission)->delete();
        }
    }
}
