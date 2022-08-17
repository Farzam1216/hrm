<?php


namespace App\Domain\Employee\Actions;

class PermissionsToAccessEmployeeInformation
{
    /**
     *check if current user can access basic information page of given employees (passed as parameter).
     */
    public function execute($data)
    {
        $basicInformation = null;
        foreach ($data as $model => $employeePermissions) {
            foreach ($employeePermissions as $employee => $permissions) {
                if ($model == 'employee' || $model == 'timeofftype') {
                    if (array_search('edit', $permissions) !== false || array_search(
                        'edit_with_approval',
                        $permissions
                    ) !== false || array_search('view', $permissions) !== false) {
                        $basicInformation[$model][$employee] = true;
                    } else {
                        $basicInformation[$model][$employee] = false;
                    }
                }
            }
        }

        return $basicInformation;
    }
}
