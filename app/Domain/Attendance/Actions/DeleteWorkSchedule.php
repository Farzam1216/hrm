<?php


namespace App\Domain\Attendance\Actions;


use App\Domain\Attendance\Models\WorkSchedule;
use http\Env\Request;
use Illuminate\Database\Eloquent\Model;

class DeleteWorkSchedule
{
    /**
     * add available plans in benefitGroup
     * @param $request
     */
    public function execute($id)
    {
        $workSchedule = WorkSchedule::find($id);
        if($workSchedule->delete()){
            return true;
        }else{
            return false;
        }
    }
}
