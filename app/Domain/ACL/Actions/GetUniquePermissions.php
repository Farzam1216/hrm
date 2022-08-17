<?php

namespace App\Domain\ACL\Actions;

class GetUniquePermissions
{
    /**
     * Remove duplicate Permissions and get unique permissions
     *
     * @param  Role $role
     * @param  string $type
     * @return Role
     */
    public function execute($defaultPermissions, $selectedPermission)
    {
        if (!empty($selectedPermission) && isset($selectedPermission['time_off'])) {
            if (isset($defaultPermissions['time_off']['time_off_policies'])) {
                foreach ($defaultPermissions['time_off']['time_off_policies'] as $key => $value) {
                    $timeOff = explode('-', $key);
                    $defaultPermissions['time_off']['time_off_policies'][$timeOff[1]] = $defaultPermissions['time_off']['time_off_policies'][$key];
                    unset($defaultPermissions['time_off']['time_off_policies'][$key]);
                }
            }
            if (isset($selectedPermission['time_off']['time_off_policies'])) {
                foreach ($selectedPermission['time_off']['time_off_policies'] as $key => $value) {
                    $timeOff = explode('-', $key);
                    $selectedPermission['time_off']['time_off_policies'][$timeOff[1]] = $selectedPermission['time_off']['time_off_policies'][$key];
                    unset($selectedPermission['time_off']['time_off_policies'][$key]);
                }
            }
            if (isset($defaultPermissions['time_off']['active_time_off_types'])) {
                foreach ($defaultPermissions['time_off']['active_time_off_types'] as $key => $value) {
                    $timeOff = explode('-', $key);
                    $defaultPermissions['time_off']['active_time_off_types'][$timeOff[1]] = $defaultPermissions['time_off']['active_time_off_types'][$key];
                    unset($defaultPermissions['time_off']['active_time_off_types'][$key]);
                }
            }
            if (isset($selectedPermission['time_off']['active_time_off_types'])) {
                foreach ($selectedPermission['time_off']['active_time_off_types'] as $key => $value) {
                    $timeOff = explode('-', $key);
                    $selectedPermission['time_off']['active_time_off_types'][$timeOff[1]] = $selectedPermission['time_off']['active_time_off_types'][$key];
                    unset($selectedPermission['time_off']['active_time_off_types'][$key]);
                }
            }
            if (isset($defaultPermissions['time_off']['checkbox']['can_request_time_off'])) {
                foreach ($defaultPermissions['time_off']['checkbox']['can_request_time_off'] as $key => $value) {
                    $timeOff = explode('-', $key);
                    $defaultPermissions['time_off']['checkbox']['can_request_time_off'][$timeOff[1]] = $defaultPermissions['time_off']['checkbox']['can_request_time_off'][$key];
                    unset($defaultPermissions['time_off']['checkbox']['can_request_time_off'][$key]);
                }
            }
            if (isset($selectedPermission['time_off']['checkbox']['can_request_time_off'])) {
                foreach ($selectedPermission['time_off']['checkbox']['can_request_time_off'] as $key => $value) {
                    $timeOff = explode('-', $key);
                    $selectedPermission['time_off']['checkbox']['can_request_time_off'][$timeOff[1]] = $selectedPermission['time_off']['checkbox']['can_request_time_off'][$key];
                    unset($selectedPermission['time_off']['checkbox']['can_request_time_off'][$key]);
                }
            }
        }
        $defaultPermissions = array_replace_recursive($selectedPermission, $defaultPermissions);
        $defaultPermissions =  (new RemoveDuplicatePermissions())->execute($defaultPermissions);
        $defaultPermissions = (new SortPermissions())->execute($defaultPermissions);
        return $defaultPermissions;
    }
}
