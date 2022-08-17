<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\AssignTimeOffType;
use App\Domain\TimeOff\Models\Policy;
use App\Domain\TimeOff\Models\TimeOffType;


class DeleteTimeOffType
{
    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function execute($id)
    {
        //delete assigned entries agaisnt id
        $assignTimeOff = AssignTimeOffType::where('type_id', $id)->get();
        foreach ($assignTimeOff as  $assigntimeoff) {
            $assigntimeoff->delete();
        }
        //delete policies against the id
        $assignPolicies = Policy::where('time_off_type', $id)->get();
        foreach ($assignPolicies as  $assignpolicy) {
            $assignpolicy->delete();
        }
        $manageTimeOffTypes = new ManageTimeOffTypesAndPoliciesPermissions();
        $manageTimeOffTypes->execute("timeofftype", $id, "delete");

        $timeOff = TimeOffType::find($id);
        $timeOff->delete();
    }
}
