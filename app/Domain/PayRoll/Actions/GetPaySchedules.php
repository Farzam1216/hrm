<?php

namespace App\Domain\PayRoll\Actions;

use Carbon\Carbon;
use App\Domain\PayRoll\Models\PaySchedule;
use App\Domain\Employee\Actions\GetAllEmployeesWithJobDetails;
use App\Domain\Employee\Actions\GetAuthorizedUserPermissions;

class GetPaySchedules
{
    /**
     * Store Poll Questions
     * @param $request
     */
    public function execute()
    {
        $now = Carbon::now();

        $paySchedules =  PaySchedule::with(['payScheduleDates' => function ($query) use ($now) {
            return $query->where('period_end', 'LIKE', '%'.$now->year);
        }])->get();

        $data['info'] = (new GetAllEmployeesWithJobDetails())->execute();
        $data['permissions'] = (new GetAuthorizedUserPermissions())->execute($data['info']['employee']);
        
        return $data = [
            'paySchedules' => $paySchedules,
            'permissions' => $data['permissions']
        ];
    }
}
