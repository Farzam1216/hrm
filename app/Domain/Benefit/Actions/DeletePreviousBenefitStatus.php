<?php


namespace App\Domain\Benefit\Actions;

use App\Domain\Benefit\Models\EmployeeBenefitStatus;
use Carbon\Carbon;

class DeletePreviousBenefitStatus
{
    /**
     * delete unused/expired status + update state and message for active status
     * @param $employeeBenefit_ID
     * @param $planName
     */

    public function execute($employeeBenefit_ID, $planName)
    {
        $allStatus = EmployeeBenefitStatus::where('employee_benefit_id', $employeeBenefit_ID)->orderBy('effective_date', 'asc')->get();
        foreach ($allStatus as $key => $status) {
            $index = $key;
            if (isset($allStatus[++$index])) {
                if (Carbon::parse($allStatus[$index]->effective_date)->startOfDay()->lte(Carbon::now()->startofDay())) {
                    $status->delete();
                } else {
                    if (Carbon::parse($status->effective_date)->startOfDay() == Carbon::now()->startofDay()) {
                        $message = (new GetMessageForStatusState())->execute(1, $status->enrollment_status);
                        $event = str_replace(['$plan', '$date'], [$planName, $status->effective_date], $message);
                        $statusTrackingField = json_decode($status->enrollment_status_tracking_field, true);
                        $statusTrackingField['event'] = $event;
                        $statusTrackingField['statusState'] = 1;
                        $status->enrollment_status_tracking_field = json_encode($statusTrackingField);
                        $status->save();
                    }
                }
            }
        }
    }
}
