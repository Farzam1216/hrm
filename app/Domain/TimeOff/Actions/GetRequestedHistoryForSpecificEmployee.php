<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\RequestTimeOff;

class GetRequestedHistoryForSpecificEmployee
{
    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function execute($id)
    {
        $history = RequestTimeOff::with('assignTimeOff', 'assignTimeOff.timeOffType', 'requestTimeOffDetail')
            ->where('employee_id', $id)
            ->orderBy('to', 'asc')->get();
        return response()->json($history);
    }
}
