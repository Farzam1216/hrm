<?php


namespace App\Domain\TimeOff\Actions;

class GetIntervalOfAccrualTypeQuaterlyAYear
{
    /**
     * @param $interval
     * @param $levelStartDate
     * @return mixed
     */
    public function execute($interval, $levelStartDate)
    {
        if ($levelStartDate->startOfDay()->between($interval['first']['start'], $interval['first']['end'])) {
            $newInterval = $interval;
            $newInterval['next'] = "second";
            $newInterval['current'] = "first";
            $newInterval['last'] = "fourth";
            return $newInterval;
        } elseif ($levelStartDate->between($interval['second']['start'], $interval['second']['end'])) {
            $newInterval = $interval;
            $newInterval['next'] = "third";
            $newInterval['current'] = "second";
            $newInterval['last'] = "first";
            return $newInterval;
        } elseif ($levelStartDate->between($interval['third']['start'], $interval['third']['end'])) {
            $newInterval = $interval;
            $newInterval['next'] = "fourth";
            $newInterval['current'] = "third";
            $newInterval['last'] = "second";
            return $newInterval;
        } elseif ($levelStartDate->between($interval['fourth']['start'], $interval['fourth']['end'])) {
            $newInterval = $interval;
            $newInterval['next'] = "first";
            $newInterval['current'] = "fourth";
            $newInterval['last'] = "second";
            return $newInterval;
        } else {
            $levelStartDate = $levelStartDate->addDays(1);
            return $this->execute($interval, $levelStartDate);
        }
    }
}
