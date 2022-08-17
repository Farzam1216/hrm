<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Requests\createPayroll;
use App\Http\Requests\storePayrollSlip;
use Illuminate\Support\Facades\Session;
use App\Domain\Employee\Models\Employee;
use App\Http\Requests\storePayrollMonth;
use App\Domain\PayRoll\Actions\GetPayRoll;
use App\Domain\PayRoll\Actions\StorePayRoll;
use App\Domain\PayRoll\Actions\DeletePayRoll;
use App\Domain\PayRoll\Actions\DeletePayRollByID;
use App\Domain\PayRoll\Actions\StorePayRollHistory;
use App\Domain\PayRoll\Actions\getFilteredPayrolls;
use App\Domain\Employee\Actions\GetEmployeeNonWorkingDays;
use App\Domain\Employee\Actions\GetEmployeeAttendanceAndWorkSchedule;

class PayRollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('unauthorized_user');

        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Payroll Management"],  
            ['name' => "Payrolls"]
         ];

         $data = (new GetPayRoll())->execute();
         
         return view('admin.payroll_management.index',[
            'breadcrumbs' => $breadcrumbs,
            'payrolls'=> $data['payrolls'],
            'employees'=> $data['employees'],
            'paySchedules'=> $data['paySchedules'],
        ]);

    }

    public function deletePayroll(Request $request)
    {
        $payroll = (new DeletePayRoll())->execute();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(createPayroll $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        if ($request->employee_id == 'all') {
            Session::flash('error', trans('language.Please select an employee'));
            return redirect()->back();
        }

        $data = (new GetEmployeeAttendanceAndWorkSchedule())->execute($request);
        $nonWorkingDays = (new GetEmployeeNonWorkingDays())->execute($request->employee_id);

        if ($nonWorkingDays == false) {
            Session::flash('error', trans('language.Work schedule is not assigned to employee'));
            return redirect()->back();
        }

        $countNonWorkingDays = count($nonWorkingDays);

        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Attendance Management"], ['link' => $locale."/attendance-management", 'name' => "Employees Attendance"], ['name' => "Generate Payroll"]
         ];

        return view('admin.payroll_management.create',[
            'locale'=> $locale,
            'breadcrumbs' => $breadcrumbs,
            'employee' => $data['employee'],
            'employeeAttendance' => $data['employeeAttendance'],
            'selectedDate' => $data['selectedDate'],
            'employeeID' => $request->employee_id,
            'nonWorkingDays' => $nonWorkingDays,
            'countNonWorkingDays'=>$countNonWorkingDays,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(storePayrollSlip $request)
    {
        $data = (new StorePayRoll())->execute($request);
        (new StorePayRollHistory())->execute($request);
        $employee = Employee::find($request->employeeID);

        if($request->csv == 1) {
            // Set the content type
            header('Content-type: application/csv');
            // Set the file name option to a filename of your choice.
            header('Content-Disposition: attachment; filename= '.$employee->fullname.' '.Carbon::parse($request->date)->format('M-Y').' pay-roll.csv');
            // Set the encoding
            header("Content-Transfer-Encoding: UTF-8");

            $f = fopen('php://output', 'a'); // Configure fopen to write to the output buffer

            // Write to the csv
            fputcsv($f, ["Name", "Mobile Number", "Basic Salary", "Housing Allowance", "Traveling Expanse", "Income Tax", "Bonus", "Deduction", "Custom Deduction", "Net Payable"]);
            fputcsv($f, [$employee->firstname. ' ' . $employee->lastname, $employee->contact_no, $data->basic_salary, $data->home_allowance, $data->travel_expanse, $data->income_tax, $data->bonus, round($data->deduction,2), $data->custom_deduction,  round($data->net_payable,2)]);

            // Close the file
            fclose($f);
            exit();
        }

        if (!$data) {
            Session::flash('error', trans('language.Something went wrong while storing PayRoll'));
        }
        return redirect('en/attendance-management');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(storePayrollMonth $request, $locale, $id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $locale,$id)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $payroll = (new DeletePayRollByID())->execute($id);
        Session::flash('success', trans('language.Payroll is Deleted successfully'));
        return redirect($locale.'/payroll-management')->with('locale', $locale);  
    }

    /**
     * Get filter resources through AJAX Call from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getFilteredPayrollsByAJAX(Request $request)
    {
        $payrolls = (new getFilteredPayrolls())->execute($request);
        return response()->JSON($payrolls);  
    }
}
