<?php


namespace App\Domain\Attendance\Actions;


use App\Domain\Employee\Models\Employee;
use App\Domain\Attendance\Models\EmployeeAttendance;
use Carbon\Carbon;

class getFiltersData
{
    /**
     * add available plans in benefitGroup
     * @param $request
     */
    public function execute($id)
    {
        $employees = Employee::all();
        $startMonth = Carbon::now()->startOfYear();
        $endMonth = Carbon::now()->endOfYear();
        $monthRange = \Carbon\CarbonPeriod::create($startMonth, '1 month', $endMonth);
        foreach ($monthRange as $key => $month){
           $months[$key+1] = Carbon::parse($month)->format('M');
        }
        $years = range(Carbon::now()->year, 2016);
        return $data = [
            'employees' => $employees,
            'months' => $months,
            'years' => $years
        ];
    }
}
