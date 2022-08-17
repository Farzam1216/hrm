<?php


namespace App\Domain\Benefit\Actions;

class GroupWaitingPeriod
{
    /**
     * check group waiting period if exists
     * @param $level
     * @return mixed|string
     */
    public function execute($level)
    {
        if ('manual' == $level['eligibilityType'] || 'hire_date' == $level['eligibilityType']) {
            return 'none';
        }
        if ('waiting_period' == $level['eligibilityType'] || 'month_after_waiting_period' == $level['eligibilityType']) {
            return $level['waiting_period'];
        }
    }
}
