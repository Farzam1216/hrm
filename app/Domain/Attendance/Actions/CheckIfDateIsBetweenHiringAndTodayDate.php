<?php

namespace App\Domain\Attendance\Actions;

use Illuminate\Database\Eloquent\Model;

class CheckIfDateIsBetweenHiringAndTodayDate
{
    public function execute($date, $hiring_date, $now)
    {
        if ($date->gte($hiring_date->format('Y-m-d')) && $date->lt($now->format('Y-m-d'))) {
            return true;
        } else {
            return false;
        }
    }
}
