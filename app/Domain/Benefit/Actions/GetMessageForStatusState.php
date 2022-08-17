<?php


namespace App\Domain\Benefit\Actions;

use Illuminate\Support\Facades\DB;

class GetMessageForStatusState
{
    /**
     * @param $statusState
     * @param $status
     * @return mixed
     */
    public function execute($statusState, $status)
    {
        if ($statusState == 1) {
            $message = "current_message";
        } else {
            $message = "future_message";
        }
        return DB::table('benefit_status_details')->where(['status' => $status])->pluck($message)->first();
    }
}
