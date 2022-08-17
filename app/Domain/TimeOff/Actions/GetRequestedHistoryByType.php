<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\RequestTimeOff;

class GetRequestedHistoryByType
{
    /**
     * @param $typeId
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function execute($typeId, $id)
    {
        $history = RequestTimeOff::with('assignTimeOff', 'assignTimeOff.timeOffType', 'requestTimeOffDetail')
            ->whereHas('assignTimeOff.timeOffType', function ($query) use ($typeId) {
                $query->where('id', $typeId);
            })
            ->where('employee_id', $id)
            ->orderBy('to', 'asc')->get();
        return response()->json($history);
    }
}
