<?php

namespace App\Domain\Compensation\Actions;

use App\Domain\Compensation\Models\CompensationChangeReason;
use App\Domain\Employee\Actions\GetAllEmployeesWithJobDetails;
use App\Domain\Employee\Actions\GetAuthorizedUserPermissions;

class GetCompensationChangeReasons
{
    public function execute()
    {
        $changeReasons = CompensationChangeReason::all();

        $data['info'] = (new GetAllEmployeesWithJobDetails())->execute();
        $data['permissions'] = (new GetAuthorizedUserPermissions())->execute($data['info']['employee']);
        
        return $data = [
            'changeReasons' => $changeReasons,
            'permissions' => $data['permissions']
        ];
    }
}
