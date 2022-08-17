<?php


namespace App\Domain\TimeOff\Actions;

use Carbon\Carbon;

class GetAccrualEndDate
{
    /**
     * @param $TransactionDesc
     * @return Carbon|null
     */
    public function execute($TransactionDesc)
    {
        $pattern = "/\d{4}\-\d{2}\-\d{2}/";

        preg_match_all($pattern, $TransactionDesc, $matches);
        if (isset($matches[0][1])) {
            return Carbon::parse($matches[0][1]);
        }
        if (isset($matches[0][0])) {
            return Carbon::parse($matches[0][0]);
        }
        return null;
    }
}
