<?php

namespace App\Domain\ACL\Actions;

class FillDefaultPermissions
{
    /**
     * Permission that manager can only see, custom role can edit too,
     * for all those permissions creating an additional edit permission.
     * @param array $defaultPermissions
     */
    public function execute(&$defaultPermissions): void
    {
        array_walk_recursive(
            $defaultPermissions,
            function (&$item) {
                $item = str_replace("view", "edit", $item);
            }
        );
    }
}
