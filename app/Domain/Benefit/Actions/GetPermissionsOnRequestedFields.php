<?php


namespace App\Domain\Benefit\Actions;

class GetPermissionsOnRequestedFields
{
    /**
     * @param $type
     * @param $request
     * @param $role
     * @return array
     */
    public function execute($type, $request, $role)
    {
        $permissions = $role->permissions;
        $approvalFields = [];
        foreach ($permissions as $permission) {
            foreach ($request as $field => $value) {
                if ('edit_with_approval ' . $type . ' ' . $field == $permission->name) {
                    $approvalFields = array_add($approvalFields, $field, $value);
                }
            }
        }
        return $approvalFields;
    }
}
