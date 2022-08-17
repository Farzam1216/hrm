<?php


namespace App\Domain\TimeOff\Actions;

use Carbon\Carbon;

class GetCarryOverDate
{
    /**
     * @param $carry_over_date
     * @param $joining_date
     * @return string
     */
    public function execute($carry_over_date, $employeeHireDate)
    {
        if ($carry_over_date != 'none') { //none means carry_over_amount is unlimited. Get carryover date in this if statement
            if ($carry_over_date == 'employee_hire_date') {
                return Carbon::parse($employeeHireDate)->format('d-m');
            } else {
                return Carbon::parse($carry_over_date)->format('d-m');
            }
        }
    }
}
