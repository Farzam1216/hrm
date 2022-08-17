<?php


namespace App\Domain\TimeOff\Actions;

use App\Domain\TimeOff\Models\RequestTimeOff;
use Carbon\Carbon;

class PendingRequestTimeOff
{
    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function execute($id)
    {
        $pendingRequests = RequestTimeOff::with(
            'assignTimeOff',
            'assignTimeOff.employee',
            'assignTimeOff.timeOffType',
            'requestTimeOffDetail'
        )
            ->where('employee_id', $id)->where('status', 'pending')
            ->where('from', '<', Carbon::now()->toDateString())->get();
        return $pendingRequests;
    }
}
