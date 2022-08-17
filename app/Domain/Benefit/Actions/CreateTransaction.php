<?php


namespace App\Domain\Benefit\Actions;

class CreateTransaction
{
    /**
     * @param $effectiveDate
     * @param $status
     * @param $planName
     * @param $employeeBenefitID
     * @param $statusState
     * @param string $comment
     */
    public function execute($effectiveDate, $status, $planName, $employeeBenefitID, $statusState, $comment = "Automatic Eligibility Update")
    {
        $trackingFieldData = (new CreateStatusTrackingFieldJSON())->execute($effectiveDate, $status, $planName, $statusState, $comment);
        // $statusDetails=$this->combineData($effectiveDate, $status, $trackingFieldData);
        (new CreateBenefitStatusTransaction())->execute($employeeBenefitID, $effectiveDate, $status, $trackingFieldData);
    }
}
