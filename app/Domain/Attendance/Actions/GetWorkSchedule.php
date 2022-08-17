<?php


namespace App\Domain\Attendance\Actions;


use App\Domain\Attendance\Models\WorkSchedule;
use Illuminate\Database\Eloquent\Model;

class GetWorkSchedule
{
    /**
     * add available plans in benefitGroup
     * @param $request
     */
    public function execute($id)
    {
        $workSchedule = WorkSchedule::find($id);
        return $workSchedule;
    }
}
