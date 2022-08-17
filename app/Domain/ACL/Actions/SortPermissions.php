<?php

namespace App\Domain\ACL\Actions;

use App\Domain\Benefit\Models\EmployeeBenefit;
use App\Domain\Benefit\Models\EmployeeDependent;
use App\Domain\Employee\Models\Education;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\EmployeeVisa;
use App\Domain\TimeOff\Models\TimeOffType;
class SortPermissions
{
    /* Sort permission for edit employee/manager and custom role
     * @param array $permissions
     * @return array $permissions
     */
    public function execute($defaultPermissions)
    {
        $models = [Employee::class, Education::class,  EmployeeVisa::class, EmployeeBenefit::class, EmployeeDependent::class, TimeOffType::class];

        foreach ($models as $key => $model) {
            $permissions = $model::sortPermissionKeys($defaultPermissions);
        }
        $sortedPermissions = [];
        !array_key_exists('personal', $defaultPermissions) ?: $sortedPermissions['personal'] = $defaultPermissions['personal'];
        !array_key_exists('attendance', $defaultPermissions) ?: $sortedPermissions['attendance'] = $defaultPermissions['attendance'];
        !array_key_exists('job', $defaultPermissions) ?: $sortedPermissions['job'] = $defaultPermissions['job'];
        !array_key_exists('time_off', $defaultPermissions) ?: $sortedPermissions['time_off'] = $defaultPermissions['time_off'];
        !array_key_exists('emergency', $defaultPermissions) ?: $sortedPermissions['emergency'] = $defaultPermissions['emergency'];
        !array_key_exists('document', $defaultPermissions) ?: $sortedPermissions['document'] = $defaultPermissions['document'];
        !array_key_exists('notes', $defaultPermissions) ?: $sortedPermissions['notes'] = $defaultPermissions['notes'];
        !array_key_exists('benefits', $defaultPermissions) ?: $sortedPermissions['benefits'] = $defaultPermissions['benefits'];
        !array_key_exists('assets', $defaultPermissions) ?: $sortedPermissions['assets'] = $defaultPermissions['assets'];
        !array_key_exists('Onboarding', $defaultPermissions) ?: $sortedPermissions['Onboarding'] = $defaultPermissions['Onboarding'];
        !array_key_exists('Offboarding', $defaultPermissions) ?: $sortedPermissions['Offboarding'] = $defaultPermissions['Offboarding'];
        !array_key_exists('performance', $defaultPermissions) ?: $sortedPermissions['performance'] = $defaultPermissions['performance'];
        return $sortedPermissions;
    }
}
