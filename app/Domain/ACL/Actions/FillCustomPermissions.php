<?php

namespace App\Domain\ACL\Actions;

class FillCustomPermissions
{
    /**
     * insert permission with edit
     * @param array $defaultCustomPermissions
     * @param array &$defaultPermissions
     * @return void
     */
    public function execute($defaultCustomPermissions, &$defaultPermissions)
    {
        foreach ($defaultCustomPermissions as $key => $value) {
            if (is_array($value)) {
                $this->execute($defaultCustomPermissions[$key], $defaultPermissions[$key]);
            } else {
                $defaultPermissions[$key + 1] = $value;
            }
        }
        return $defaultPermissions;
    }
}
