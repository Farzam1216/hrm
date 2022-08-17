<?php


namespace App\Domain\TimeOff\Actions;

class GetIntervalByCurrentDate
{
    /**
     * @param $interval
     * @param $tempDate
     * @return mixed
     */
    public function execute($interval, $tempDate)
    {
        if ($tempDate->startOfDay()->between($interval['first']['start']->startOfDay(), $interval['first']['end']->startOfDay())) {
            $newInterval = $interval;
            $newInterval['opposite'] = "second";
            $newInterval['current'] = "first";
            return $newInterval;
        } elseif ($tempDate->between($interval['second']['start']->startOfDay(), $interval['second']['end']->startOfDay())) {
            $newInterval = $interval;
            $newInterval['current'] = "second";
            $newInterval['opposite'] = "first";
            return $newInterval;
        } else {
            $tempDate = $tempDate->addDays(1);

            return $this->execute($interval, $tempDate);
        }
    }
}
