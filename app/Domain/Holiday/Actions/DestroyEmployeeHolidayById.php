<?php


namespace App\Domain\Holiday\Actions;

use App\Domain\Employee\Models\EmployeeHoliday;

class DestroyEmployeeHolidayById
{

    /**
     * @return mixed
     */
    public function execute($id)
    {
        $employee_holidays = EmployeeHoliday::where('holiday_id', $id)->get();

        foreach ($employee_holidays as $employee_holiday) {
            EmployeeHoliday::destroy($employee_holiday->id);
        }
        
        return $employee_holidays;
    }
}
