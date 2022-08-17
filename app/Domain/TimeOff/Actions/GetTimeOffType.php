<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\TimeOffType;
use Carbon\Carbon;

class GetTimeOffType
{
    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function execute($id)
    {
        return TimeOffType::find($id);
    }
}
