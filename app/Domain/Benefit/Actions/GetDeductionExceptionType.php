<?php


namespace App\Domain\Benefit\Actions;

class GetDeductionExceptionType
{
    /**
     * check employee will pay at each paycheck or skip some
     * @param $level
     * @return mixed|string
     */
    public function execute($level)
    {
        if ('yes' == $level['deduction_exception']) {
            return 'none';
        }
        if ('no' == $level['deduction_exception']) {
            return $level['deductionExceptionType'];
        }
    }
}
