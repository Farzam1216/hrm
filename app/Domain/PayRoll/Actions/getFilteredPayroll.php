<?php


namespace App\Domain\Payroll\Actions;

use App\Domain\PayRoll\Models\PayrollHistory;

class getFilteredPayroll
{
    /**
     * add available plans in benefitGroup
     * @param $request
     */
    public function execute($request)
    {
        
        if ($request->id == 'all') {
            $payrollHistory = PayrollHistory::with('employee')->orderBy('created_at','asc')->get();
        }

        if ($request->id != 'all') {
            $payrollHistory = PayrollHistory::where('employee_id',$request->id)->with('employee')->orderBy('created_at','asc')->get();
        }

        if ($request->id == 'all' && $request->year) {
            $year = $request->year;
            $payrollHistory = PayrollHistory::whereYear('created_at', '=', $year)->with('employee')->orderBy('created_at','asc')->get();
        }

        if ($request->id == 'all' && $request->month) {
            $month = $request->month;
            $payrollHistory = PayrollHistory::whereMonth('created_at', '=', $month)->with('employee')->orderBy('created_at','asc')->get();
        }

        if ($request->id == 'all' && $request->month && $request->year) {
            $year = $request->year;
            $month = $request->month;
            $payrollHistory = PayrollHistory::whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $year)->with('employee')->orderBy('created_at','asc')->get();
        }

        if ($request->id != 'all' && $request->month) {
            $month = $request->month;
            $payrollHistory = PayrollHistory::where('employee_id', $request->id)->whereMonth('created_at', '=', $month)->with('employee')->orderBy('created_at','asc')->get();
        }

        if ($request->id != 'all' && $request->year) {
            $year = $request->year;
            $payrollHistory = PayrollHistory::where('employee_id', $request->id)->whereYear('created_at', '=', $year)->with('employee')->orderBy('created_at','asc')->get();
        }

        if ($request->id != 'all' && $request->month && $request->year) {
            $year = $request->year;
            $month = $request->month;
            $payrollHistory = PayrollHistory::where('employee_id', $request->id)->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $year)->with('employee')->orderBy('created_at','asc')->get();
        }


        return $payrollHistory;
    }
}
