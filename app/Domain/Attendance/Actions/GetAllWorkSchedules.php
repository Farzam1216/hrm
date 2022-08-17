<?php


namespace App\Domain\Attendance\Actions;


use App\Domain\Attendance\Models\WorkSchedule;
use Illuminate\Database\Eloquent\Model;

class GetAllWorkSchedules
{
    /**
     * add available plans in benefitGroup
     * @param $request
     */
    public function execute()
    {
        $workSchedules = WorkSchedule::all();
        return $workSchedules;
    }
}
