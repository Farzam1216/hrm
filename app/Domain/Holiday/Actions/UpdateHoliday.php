<?php


namespace App\Domain\Holiday\Actions;

use Carbon\Carbon;
use App\Domain\Holiday\Models\Holiday;
use App\Domain\Employee\Models\Employee;
use App\Domain\Holiday\Actions\AssignHolidayToEmployees;
use App\Domain\Holiday\Actions\DestroyEmployeeHolidayById;

class UpdateHoliday
{
    /**
     * @return mixed
     */
    public function execute($request, $id)
    {
        $holiday = Holiday::find($id);

        $delete_employee_holidays = (new DestroyEmployeeHolidayById())->execute($id);

        $holiday->name = $request->holiday_name;
        if ($request->single_date) {
            $holiday->date = $request->single_date;
        } else {
            $holiday->date = $request->multiple_dates;
        }
        $holiday->pay_rate = $request->pay_rate;
        $holiday->assigned_to_filters = $request->assigned_to_filter;
        $holiday->save();

        $employees = Employee::with([
            'jobs' => function ($query) {$query->where('effective_date', '<=', Carbon::now()->format('Y-m-d'))->orderBy('effective_date', 'desc');},
            'employment_status' => function ($query) {$query->where('effective_date', '<=', Carbon::now()->format('Y-m-d'))->orderBy('effective_date', 'desc');}
        ])->get();

        (new AssignHolidayToEmployees())->execute($request, $holiday, $employees);
        
        return $holiday;
    }
}
