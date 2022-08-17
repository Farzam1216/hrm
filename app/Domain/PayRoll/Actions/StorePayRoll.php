<?php

namespace App\Domain\PayRoll\Actions;

use App\Domain\PayRoll\Models\PayRoll;
use Carbon\Carbon;
use Session;

class StorePayRoll
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
        $payroll = PayRoll::where('employee_id', $request->employeeID)->where('month_year', $date)->first();

        if (!$payroll) {
            $payroll = new PayRoll();
            Session::flash('success', trans('language.PayRoll is stored successfully'));
        } else {
            Session::flash('success', trans('language.Existing pay-roll is updated against employee for selected date successfully'));
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

        $payroll->employee_id = $request->employeeID;
        $payroll->basic_salary = $request->basicSalary;
        $payroll->home_allowance = $request->homeAllowance;
        $payroll->travel_expanse = $request->travelingExpanse;
        $payroll->income_tax = $request->incomeTax;
        $payroll->bonus = $request->bonus;
        $payroll->custom_deduction = $request->custom_deduction;
        $payroll->deduction = round($deduction);
        $payroll->net_payable = round($netPayable);
        $payroll->absent_count = $absentsCount;
        $payroll->month_year = $date->format('m-Y');
        $payroll->save();

        return $payroll;
    }
}
