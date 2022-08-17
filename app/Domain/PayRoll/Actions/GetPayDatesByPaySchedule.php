<?php

namespace App\Domain\PayRoll\Actions;

use Carbon\Carbon;
use App\Domain\Holiday\Models\Holiday;
use App\Domain\PayRoll\Actions\GetPayDatesByFrequencyTypeWeekly;
use App\Domain\PayRoll\Actions\GetPayDatesByFrequencyTypeEveryOtherWeek;
use App\Domain\PayRoll\Actions\GetPayDatesByFrequencyTypeTwiceAMonthly;
use App\Domain\PayRoll\Actions\GetPayDatesByFrequencyTypeTwiceAYearly;

class GetPayDatesByPaySchedule
{
    /**
     * Store Poll Questions
     * @param $request
     */
    public function execute($request)
    {
        $holidays = Holiday::all();
        $today = Carbon::now();
        $data = [];
        $index = 0;

        if ($request->frequency == 'Weekly') {
            $dates = (new GetPayDatesByFrequencyTypeWeekly())->execute($request, $holidays, $today, $data, $index);
        }

        if ($request->frequency == 'Every other week') {
            $dates = (new GetPayDatesByFrequencyTypeEveryOtherWeek())->execute($request, $holidays, $today, $data, $index);
        }
        if ($request->frequency == 'Twice a monthly') {
            $dates = (new GetPayDatesByFrequencyTypeTwiceAMonthly())->execute($request, $holidays, $today, $data, $index);
        }
        if ($request->frequency == 'Monthly') {
            $dates = (new GetPayDatesByFrequencyTypeMonthly())->execute($request, $holidays, $today, $data, $index);
        }
        if ($request->frequency == 'Quarterly') {
            $dates = (new GetPayDatesByFrequencyTypeQuarterly())->execute($request, $holidays, $today, $data, $index);
        }
        if ($request->frequency == 'Twice a yearly') {
            $dates = (new GetPayDatesByFrequencyTypeTwiceAYearly())->execute($request, $holidays, $today, $data, $index);
        }
        if ($request->frequency == 'Yearly') {
            $dates = (new GetPayDatesByFrequencyTypeYearly())->execute($request, $holidays, $today, $data, $index);
        }
        
        return $dates;
    }
}
