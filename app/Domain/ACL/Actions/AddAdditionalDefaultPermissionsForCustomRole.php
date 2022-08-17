<?php

namespace App\Domain\ACL\Actions;

use App\Domain\Benefit\Models\BenefitPlan;

class AddAdditionalDefaultPermissionsForCustomRole
{
    /**
     * Add additional permissions for custom Role
     * @return defaultPermissions
     */
    public function execute($defaultPermissions)
    {
        $defaultCustomPermissions = $defaultPermissions; //holding default Manager and custom Permissions.
        (new FillDefaultPermissions())->execute($defaultPermissions);
        $defaultCustomPermissions = (new FillCustomPermissions())->execute($defaultCustomPermissions, $defaultPermissions);
        $defaultCustomPermissions = (new RemoveDuplicatePermissions())->execute($defaultCustomPermissions);
        $defaultCustomPermissions = (new BenefitPlan())->getBenefitPlanPermissions($defaultCustomPermissions);
        return $defaultCustomPermissions;
    }
}
