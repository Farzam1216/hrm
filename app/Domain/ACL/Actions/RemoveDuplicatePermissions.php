<?php

namespace App\Domain\ACL\Actions;

class RemoveDuplicatePermissions
{
    /**
     * remove duplicate values from multidimensional array
     * Note:(Old name super_unique)
     * @param array $permission
     * @return array $uniqueArray
     */
    public function execute($permission)
    {
        $result = array_map("unserialize", array_unique(array_map("serialize", $permission)));

        foreach ($result as $key => $value) {
            if (is_array($value)) {
                $result[$key] = $this->execute($value);
            }
        }
        return $result;
    }
}
