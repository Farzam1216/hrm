<?php


namespace App\Domain\Benefit\Actions;

use Illuminate\Support\Facades\Auth;

class CreateStatusTrackingFieldJSON
{
    /**
     * @param $effectiveDate
     * @param $enrollmentStatus
     * @param $planName
     * @param $statusState
     * @param string $comment
     * @return false|string
     */
    public function execute($effectiveDate, $enrollmentStatus, $planName, $statusState, $comment)
    {
        $statusMessage = (new GetMessageForStatusState())->execute($statusState, $enrollmentStatus);
        $event = str_replace(['$plan', '$date'], [$planName, $effectiveDate->format('d-m-Y')], $statusMessage);
        $status['created_by'] = Auth::user()->firstname;
        $status['statusState'] = $statusState;
        $status['event'] = $event;
        $status['comment'] = $comment;
        return json_encode($status);
    }
}
