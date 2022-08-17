<?php


namespace App\Domain\TimeOff\Actions;

use App\Models\RequestTimeOffDetail;
use Illuminate\Database\Eloquent\Builder;

class GetRequestedTimeOffDetailsByEmployee
{
    /**
     * @param $userID
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function execute($userID)
    {
        return RequestTimeOffDetail::with('requestTimeOff', 'requestTimeOff.assignTimeOff')
            ->whereHas('requestTimeOff.assignTimeOff', function (Builder $query) use ($userID) {
                $query->where('employee_id', $userID);
            })->get();
    }
}
