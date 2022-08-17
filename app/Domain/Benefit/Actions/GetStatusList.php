<?php


namespace App\Domain\Benefit\Actions;

use Illuminate\Support\Facades\DB;

class GetStatusList
{
    /**
     * return possible statuses list depending upon the current status of employee's benefit plan
     * @param $benefitStatus
     * @return array|mixed
     */
    public function execute($benefitStatus)
    {
        $statusList = [];
        $count = 0;
        foreach ($benefitStatus as $key => $status) {
            //We can't use key because first index will not always be 0
            if ($count == 0) {
                $currentStatus = DB::table('benefit_status_details')->where('status', $status->enrollment_status)->first();
                $statusList = json_decode($currentStatus->status_lists, true);
                $count++;
            } else {
                foreach ($statusList as $key => $statusAttr) {
                    if ($status->enrollment_status == $statusAttr['value']) {
                        $statusList[$key]['label'] = DB::table('benefit_status_details')->where('status', $status->enrollment_status)->pluck('status_edit_form')->first();
                    }
                }
            }
        }
        return $statusList;
    }
}
