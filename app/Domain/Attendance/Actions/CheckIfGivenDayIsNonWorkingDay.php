<?php

namespace App\Domain\Attendance\Actions;

use Illuminate\Database\Eloquent\Model;

class CheckIfGivenDayIsNonWorkingDay
{
    public function execute($dayName, $nonWorkingDays)
    {
        if (in_array(strToLower($dayName), $nonWorkingDays)) {
            return true;
        } else {
            return false;
        }
    }
}
