<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\RequestTimeOff;

class GetRequestedHistoryByYear
{
    /**
     * @param $getyear
     * @param $id
     * @return \Illuminate\Http\JsonResponse\
     */
    public function execute($getyear, $id)
    {
        $history = RequestTimeOff::with('assignTimeOff', 'assignTimeOff.timeOffType', 'requestTimeOffDetail')
            ->where('employee_id', $id)
            ->where('from', 'like', $getyear . '%')
            ->orderBy('to', 'asc')->get();
        return response()->json($history);
    }
}
