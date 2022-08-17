<?php


namespace App\Domain\PayRoll\Actions;

use App\Domain\PayRoll\Models\PayRoll;
use App\Domain\PayRoll\Models\PaySchedule;

class getFilteredPayrolls
{
    /**
     * add available plans in benefitGroup
     * @param $request
     */
    public function execute($request)
    {
        $payrolls = [];
        if ($request->employee_id == 'all' && $request->pay_schedule_id == 'all') {
            $payrolls[0] = Payroll::with('employee')->orderBy('created_at','asc')->get();
        }

        if ($request->employee_id != 'all' && $request->pay_schedule_id == 'all') {
            $payrolls[0] = Payroll::where('employee_id', $request->employee_id)->with(['employee'])->orderBy('created_at','asc')->get();
        }

        if ($request->employee_id == 'all' && $request->pay_schedule_id != 'all') {
            $pay_schedule_id = PaySchedule::where('id', $request->pay_schedule_id)->with('assignedEmployees')->first();
            foreach ($pay_schedule_id['assignedEmployees'] as $key => $assigned_employee) {
                $payrolls[$key++] = Payroll::where('employee_id', $assigned_employee->employee_id)->with('employee')->orderBy('created_at','asc')->get();
            }
        }

        if ($request->employee_id != 'all' && $request->pay_schedule_id != 'all') {
            $pay_schedule_id = PaySchedule::where('id', $request->pay_schedule_id)->with('assignedEmployees')->first();
            foreach ($pay_schedule_id['assignedEmployees'] as $key => $assigned_employee) {
                if ($assigned_employee->employee_id == $request->employee_id) {
                    $payrolls[$key++] = Payroll::where('employee_id', $assigned_employee->employee_id)->with('employee')->orderBy('created_at','asc')->get();
                }
            }
        }

        return $payrolls;
    }
}
