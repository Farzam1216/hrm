<?php

namespace App\Domain\ACL\Actions;

class GetModelPermissions
{
    public function execute($model, $roleType, $currentPermissions)
    {
        $data = $model::getDefaultPermissions($roleType, $currentPermissions);
        return $data;
    }
}
