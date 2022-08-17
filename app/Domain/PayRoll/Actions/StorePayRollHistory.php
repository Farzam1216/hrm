<?php

namespace App\Domain\PayRoll\Actions;

use App\Domain\PayRoll\Models\PayrollHistory;
use Carbon\Carbon;
class StorePayRollHistory
{
    /**
     * Store Poll Questions
     * @param $request
     */
    public function execute($request)
    {
        $date = Carbon::parse($request->date)->format('m-Y');
        $totalDays = count($request->created_at);

        $employeeAttendance = [];
        $payrollHistory = PayrollHistory::where('employee_id', $request->employeeID)->where('month_year', $date)->first();

        if (!$payrollHistory) {
            $payrollHistory = new PayrollHistory();
        }

        for ($i = 0; $i < $totalDays; $i++) {
            $date = Carbon::parse($request->created_at[$i]);
            
            if (isset($request->status[$i])) {
                $status = $request->status[$i];
            } else {
                $status = "Present";
            }

            $employeeAttendance[$i] = [
                'time_in' => $request->time_in[$i],
                'time_out' => $request->time_out[$i],
                'time_in_status' => $request->time_in_status[$i],
                'status' => $status,
                'day' => $request->day[$i],
                'created_at' => $request->created_at[$i],
            ];
        }

        $absentsCount = 0;
        $holidaysCount = 0;
        foreach ($employeeAttendance as $attendance) {
            if ($attendance['status'] == 'absent') {
                $absentsCount++;
            }
            if ($attendance['status'] == 'holiday') {
                $holidaysCount++;
            }
        }

        $totalDaysWorked = $totalDays - $absentsCount;
        $salary = ($request->basicSalary / $totalDays )* $totalDaysWorked;
        $deduction = ($request->basicSalary - $salary);
        $netPayable = $salary + $request->homeAllowance + $request->travelingExpanse + $request->bonus - $request->incomeTax - $request->custom_deduction;

        $payrollHistory->employee_id = $request->employeeID;
        $payrollHistory->basic_salary = $request->basicSalary;
        $payrollHistory->home_allowance = $request->homeAllowance;
        $payrollHistory->travel_expense = $request->travelingExpanse;
        $payrollHistory->income_tax = $request->incomeTax;
        $payrollHistory->bonus = $request->bonus;
        $payrollHistory->custom_deduction = $request->custom_deduction;
        $payrollHistory->deduction = round($deduction, 2);
        $payrollHistory->net_payable = round($netPayable, 2);
        $payrollHistory->absent_count = $absentsCount;
        $payrollHistory->month_year = $date->format('m-Y');
        $payrollHistory->created_at = $date->format('d-m-Y');
        $payrollHistory->save();

        return $payrollHistory;
    }
}
