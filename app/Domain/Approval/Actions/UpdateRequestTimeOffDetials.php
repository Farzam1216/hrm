<?php


namespace App\Domain\Approval\Actions;

use App\Models\RequestTimeOffDetail;

class UpdateRequestTimeOffDetials
{
    /**
     * @param $data
     * @param $id
     * @return int|mixed
     */
    public function execute($data, $id)
    {
        $sumOfHours = 0;
        foreach ($data['dailyAmount'] as $date => $hours) {
            $timeOffDetail = new RequestTimeOffDetail();
            $timeOffDetail->request_time_off_id = $id;
            $timeOffDetail->date = $date;
            $timeOffDetail->hours = $hours;
            $timeOffDetail->save();
            $sumOfHours += $hours;
        }
        return $sumOfHours;
    }
}
