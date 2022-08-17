<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Employee\Models\Branch;
use App\Domain\Employee\Models\Employee;
use App\Domain\TimeOff\Leave;
use App\Models\Payroll\Allowance;
use App\Models\Payroll\Deduction;
use App\Models\Payroll\SalaryTemplate;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Session;

class SalariesController extends Controller
{

    /**
     * Salary Table with Absents And leaves
     * @param $lang
     * @param string $id Year Parameter
     * @param Request $request
     * @return mixed
     */
    public function index($lang, $id = "", Request $request)
    {
        if ($id == "") {
            $id = Carbon::now()->format('Y-m');
        }
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $currentMonth = Carbon::parse($id)->format('m');
        $currentYear = Carbon::parse($id)->format('Y');
        $UnApprovedCount = [];
        $approvedCount = [];
        $absentDeduction = [];
        $leavesDeduction = [];
        $netPayables = [];
        $employeeApprovedLeaves = [];
        $salaryEmployees = Employee::where('status', '!=', '0')->get();

        foreach ($salaryEmployees as $employee) {
            $weekend = Branch::where('id', $employee->location_id)->first();
            //Present Dates
            $attendance_summaries = AttendanceSummary::where(
                'employee_id',
                $employee->id
            )->whereRaw('MONTH(first_timestamp_in) = ?', [$currentMonth])->whereRaw(
                'YEAR(first_timestamp_in) = ?',
                [$currentYear]
            )->get();
            $presentDate = [];
            if ($attendance_summaries->count() > 0) {
                foreach ($attendance_summaries as $key => $value) {
                    $presentDate[] = $value->date;
                }
            }

            $present[$employee->id] = AttendanceSummary::where(
                'employee_id',
                $employee->id
            )->whereRaw('MONTH(first_timestamp_in) = ?', [$currentMonth])->whereRaw(
                'YEAR(first_timestamp_in) = ?',
                [$currentYear]
            )->count();
            ///////////
            ///Un Approved Leaves
            $unApprovedLeaveDate = [];
            $unApprovedPeriods = [];
            $unAapprovedLeaves = Leave::where('employee_id', $employee->id)->where(
                'status',
                'Declined'
            )->whereRaw('MONTH(datefrom) = ?', $currentMonth)->whereRaw('YEAR(datefrom) = ?', $currentYear)->get();

            foreach ($unAapprovedLeaves as $unApprovedLeave) {
                $approvedPeriods[] = CarbonPeriod::create($unApprovedLeave->datefrom, $unApprovedLeave->dateto);
            }
            foreach ($unApprovedPeriods as $unApprovedPeriod) {
                foreach ($unApprovedPeriod as $unApprovedDates) {
                    $unApprovedLeaveDate[] = $unApprovedDates->format('Y-m-d');
                }
            }

            $unApproved = [];
            foreach ($unApprovedLeaveDate as $unLeaveDate) {
                if (date(
                    'm',
                    strtotime($unLeaveDate)
                ) == $currentMonth && in_array(
                    Carbon::parse($unLeaveDate)->format('l'),
                    json_decode($weekend->weekend)
                ) == false) {
                    $unApproved[] = $unLeaveDate;
                }
            }
            $employeeUnApprovedLeaves[$employee->id] = count($unApproved);

            /*Approved Leaves*/
            $approvedLeaveDate = [];
            $approvedPeriods = [];
            $approvedLeaves = Leave::where('employee_id', $employee->id)->where(
                'status',
                'Approved'
            )->whereRaw('MONTH(datefrom) = ?', $currentMonth)->whereRaw('YEAR(datefrom) = ?', $currentYear)->get();

            foreach ($approvedLeaves as $approvedLeave) {
                $approvedPeriods[] = CarbonPeriod::create($approvedLeave->datefrom, $approvedLeave->dateto);
            }
            foreach ($approvedPeriods as $approvedPeriod) {
                foreach ($approvedPeriod as $approvedDates) {
                    $approvedLeaveDate[] = $approvedDates->format('Y-m-d');
                }
            }

            $Approved = [];
            foreach ($approvedLeaveDate as $leaveDate) {
                if (date(
                    'm',
                    strtotime($leaveDate)
                ) == $currentMonth && in_array(
                    Carbon::parse($leaveDate)->format('l'),
                    json_decode($weekend->weekend)
                ) == false) {
                    $Approved[] = $leaveDate;
                }
            }
            $employeeApprovedLeaves[$employee->id] = count($Approved);


            /////////
            $numberOfDays = cal_days_in_month(CAL_GREGORIAN, $currentMonth, Carbon::parse($id)->year);
            $workingDays = 0;
            $mothDays = 0;
            for ($i = 1; $i <= $numberOfDays; $i++) {
                $date = Carbon::parse($i . "-" . $currentMonth . "-" . Carbon::parse($id)->year)->toDateString();
                $mothDays += 1;
                if (in_array(Carbon::parse($date)->format('l'), json_decode($weekend->weekend)) == false) {
                    $workingDays += 1;
                }
            }

            /////Absents
            $absent = [];
            for ($i = 1; $i <= $mothDays; $i++) {
                $date = Carbon::parse($i . "-" . $currentMonth . "-" . Carbon::parse($id)->format('Y'))->toDateString();
                if (!in_array($date, $presentDate) && in_array(
                    Carbon::parse($date)->format('l'),
                    json_decode($weekend->weekend)
                ) == false && in_array(
                    Carbon::parse($date)->toDateString(),
                    $Approved
                ) == false && in_array(Carbon::parse($date)->toDateString(), $unApproved) == false) {
                    $absent[] = "";
                }
            }
            $addAllowance = 0;
            $addDeduction = 0;
            $template = SalaryTemplate::where('id', $employee->salary_template)->first();
            if (isset($template)) {
                $allowances = Allowance::where('template_id', $employee->salary_template)->get();
                $deductions = Deduction::where('template_id', $employee->salary_template)->get();
                if (isset($allowances)) {
                    foreach ($allowances as $allowance) {
                        if ($allowance->type == 1) {
                            $amount1 = $allowance->amount;
                        } else {
                            $amount1 = $template->basic_salary * ($allowance->amount / 100);
                        }
                        $addAllowance = $addAllowance + $amount1;
                    }
                } else {
                    $addAllowance = 0;
                }
                if (isset($deductions)) {
                    foreach ($deductions as $deduction) {
                        if ($deduction->type == 1) {
                            $amount2 = $deduction->amount;
                        } else {
                            $amount2 = $template->basic_salary * ($deduction->amount / 100);
                        }
                        $addDeduction = $addDeduction + $amount2;
                    }
                } else {
                    $addDeduction = 0;
                }
            } else {
                $addAllowance = 0;
                $addDeduction = 0;
            }
            $AbsentCount[$employee->id] = count($absent);
            $approvedCount[$employee->id] = $employeeApprovedLeaves[$employee->id];
            $unApprovedCount[$employee->id] = $employeeUnApprovedLeaves[$employee->id];
            if (isset($template->deduction) && $template->deduction == 1) {
                $absentDeduction[$employee->id] = ($template->basic_salary / $workingDays) * $AbsentCount[$employee->id];
            } else {
                $absentDeduction[$employee->id] = 0;
            }
            if (isset($template->basic_salary)) {
                $netPayables[$employee->id] = round($template->basic_salary + ($employee->bonus) + ($addAllowance) - $absentDeduction[$employee->id] - ($addDeduction));
            } else {
                $netPayables[$employee->id] = 0;
            }
        }

        $template = SalaryTemplate::all();
        $employees = Employee::where('status', '!=', '0')->get();
        return view('admin.salary.index')->with('month', $id)->with('employees', $employees)->with(
            'ApprovedCount',
            $approvedCount
        )->with('unApprovedCount', $unApprovedCount)->with(
            'netPayables',
            $netPayables
        )->with('AbsentCounts', $AbsentCount)->with('presents', $present)
            ->with('template', $template)->with('locale', $locale);
    }

    /**
     * add bonus to specific resource
     * @param Request $request
     * @param string $id Year Parameter
     * @return mixed
     */
    public function addBonus(Request $request, $lang, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $bonus = Employee::find($id);
        $bonus->bonus = $request->bonus;
        $bonus->save();
        Session::flash('success', trans('language.Bonus Added Successfully'));
        return redirect()->back()->with('locale', $locale);
    }

    /**
     * Show All Salary Templates
     * @param Request $request
     * @return mixed
     */
    public function salaryTemplates(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $salaryTemplates = SalaryTemplate::all();
        return view('admin.salary.salarytemplate')->with('templates', $salaryTemplates)->with('locale', $locale);
    }

    /**
     * Store Salary Template
     * @param Request $request
     * @return mixed
     */
    public function addSalaryTemplates(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $template = new SalaryTemplate();
        $template->template_name = $request->name;
        $template->basic_salary = $request->salary;
        $template->status = $request->status;
        $template->deduction = $request->deduction;
        $template->save();
        Session::flash('success', trans('language.Salary Template Added Successfully'));
        return redirect()->back()->with('locale', $locale);
    }

    /**
     * Update Salary Template Details
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function updateSalaryTemplates(Request $request, $lang, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $template = SalaryTemplate::find($id);
        $template->template_name = $request->name;
        $template->basic_salary = $request->salary;
        $template->status = $request->status;
        $template->deduction = $request->deduction;
        $template->save();
        Session::flash('success', trans('language.Salary Template Updated Successfully'));
        return redirect()->back()->with('locale', $locale);
    }

    /**
     * Delete Salary Templates
     * @param $lang
     * @param string $id
     * @param Request $request
     * @return mixed
     */
    public function deleteSalaryTemplates($lang, $id, Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $template = SalaryTemplate::find($id);
        $template->delete();
        Session::flash('success', trans('language.Salary Template Deleted Successfully'));
        return redirect()->back()->with('locale', $locale);
    }

    /**
     * Show Page For Add Alowance And Deductions
     * @param $lang
     * @param $id
     * @param Request $request
     * @return Factory|View
     */
    public function manageSalaryTemplate($lang, $id, Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $allowances = Allowance::where('template_id', $id)->get();
        $template = SalaryTemplate::where('id', $id)->first();
        $deductions = Deduction::where('template_id', $id)->get();
        return view('admin.salary.managesalarytemplate')
            ->with('template', $template)
            ->with('allowances', $allowances)
            ->with('locale', $locale)
            ->with('deductions', $deductions);
    }

    /**
     * Add Allowance To Template
     * @param Request $request
     * @return Factory|View
     */
    public function addAllowance(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $allowance = new Allowance();
        $allowance->template_id = $request->template_id;
        $allowance->allowance_name = $request->name;
        $allowance->amount = $request->amount;
        $allowance->type = $request->type;
        $allowance->save();
        Session::flash('success', trans('language.Allowance Added Successfully'));
        return redirect()->back()->with('locale', $locale);
    }

    /**
     * Add Deduction To Template
     * @param Request $request
     * @return Factory|View
     */
    public function addDeduction(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $deduction = new Deduction();
        $deduction->template_id = $request->template_id;
        $deduction->deduction_name = $request->name;
        $deduction->amount = $request->amount;
        $deduction->type = $request->type;
        $deduction->save();
        Session::flash('success', trans('language.Deduction Added Successfully'));
        return redirect()->back()->with('locale', $locale);
    }


    /**
     * Update Allowance Details
     * @param Request $request
     * @param $id
     * @return Factory|View
     */
    public function updateAllowance(Request $request, $lang, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $allowance = Allowance::find($id);
        $allowance->template_id = $request->template_id;
        $allowance->allowance_name = $request->name;
        $allowance->amount = $request->amount;
        $allowance->type = $request->type;
        $allowance->save();
        Session::flash('success', trans('language.Allowance Updated Successfully'));
        return redirect()->back()->with('locale', $locale);
    }


    /**
     * Update Deduction Details
     * @param Request $request
     * @param $id
     * @return Factory|View
     */
    public function updateDeduction(Request $request, $lang, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $deduction = Deduction::find($id);
        $deduction->template_id = $request->template_id;
        $deduction->deduction_name = $request->name;
        $deduction->amount = $request->amount;
        $deduction->type = $request->type;
        $deduction->save();
        Session::flash('success', trans('language.Deduction Updated Successfully'));
        return redirect()->back()->with('locale', $locale);
    }


    /**
     * Delete Allowance From The Template
     * @param $id
     * @return Factory|View
     */
    public function deleteAllowance($lang, $id, Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $allowance = Allowance::find($id);
        $allowance->delete();
        Session::flash('success', trans('language.Allowance Deleted Successfully'));
        return redirect()->back()->with('locale', $locale);
    }

    /**
     * Delete Deduction From The Template
     * @param $id
     * @param Request $request
     * @return Factory|View
     */
    public function deleteDeduction($id, Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $deduction = Deduction::find($id);
        $deduction->delete();
        Session::flash('success', trans('language.Deduction Deleted Successfully'));
        return redirect()->back()->with('locale', $locale);
    }

    /**
     * Show Form For Assign Template To Employee
     * @param Request $request
     * @return Factory|View
     */
    public function manageSalary(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $employees = Employee::with('salaryTemplate')->where('status', '1')->get();
        $templates = SalaryTemplate::where('status', '1')->get();

        return view('admin.salary.managesalary')->with('employees', $employees)->with('templates', $templates)->with('locale', $locale);
    }

    /**
     * Store Assigned Template
     * @param Request $request
     * @return Factory|View
     */
    public function assignTemplate(Request $request)
    {
        $employee = Employee::where('id', $request->employee_id)->first();
        $employee->salary_template = $request->template;
        $employee->save();
        return redirect()->back();
    }

    /**
     * Show Form To Generate Salary Slip
     * @param Request $request
     * @return Factory|View
     */
    public function generateSalarySlip(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $employee = Employee::where('status', 1)->get();
        return view('admin.salary.generatesalaryslip')->with('employees', $employee)->with('locale', $locale);
    }

    /**
     * Show Generated Salary Slip
     * @param Request $request
     * @return Factory|View
     */
    public function salarySlip(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        if ($request->month == null) {
            $id = Carbon::now()->format('Y-m');
        } else {
            $id = $request->month;
        }

        $currentMonth = Carbon::parse($id)->format('m');
        $currentYear = Carbon::parse($id)->format('Y');
        $UnApprovedCount = [];
        $approvedCount = [];
        $absentDeduction = [];
        $leavesDeduction = [];
        $netPayables = [];
        $employeeApprovedLeaves = [];
        $salaryEmployees = Employee::where('id', $request->employee_id)->get();
        $employee = Employee::with('branch', 'department')->where('id', $request->employee_id)->first();
        $weekend = Branch::where('id', $employee->location_id)->first();
        //Present Dates
        $attendance_summaries = AttendanceSummary::where(
            'employee_id',
            $employee->id
        )->whereRaw('MONTH(first_timestamp_in) = ?', [$currentMonth])->whereRaw(
            'YEAR(first_timestamp_in) = ?',
            [$currentYear]
        )->get();
        $presentDate = [];
        if ($attendance_summaries->count() > 0) {
            foreach ($attendance_summaries as $key => $value) {
                $presentDate[] = $value->date;
            }
        }

        $present[$employee->id] = AttendanceSummary::where(
            'employee_id',
            $employee->id
        )->whereRaw('MONTH(first_timestamp_in) = ?', [$currentMonth])->whereRaw(
            'YEAR(first_timestamp_in) = ?',
            [$currentYear]
        )->count();
        ///////////
        ///Un Approved Leaves
        $unApprovedLeaveDate = [];
        $unApprovedPeriods = [];
        $unAapprovedLeaves = Leave::where('employee_id', $employee->id)->where(
            'status',
            'Declined'
        )->whereRaw('MONTH(datefrom) = ?', $currentMonth)->whereRaw('YEAR(datefrom) = ?', $currentYear)->get();

        foreach ($unAapprovedLeaves as $unApprovedLeave) {
            $approvedPeriods[] = CarbonPeriod::create($unApprovedLeave->datefrom, $unApprovedLeave->dateto);
        }
        foreach ($unApprovedPeriods as $unApprovedPeriod) {
            foreach ($unApprovedPeriod as $unApprovedDates) {
                $unApprovedLeaveDate[] = $unApprovedDates->format('Y-m-d');
            }
        }

        $unApproved = [];
        foreach ($unApprovedLeaveDate as $unLeaveDate) {
            if (date(
                'm',
                strtotime($unLeaveDate)
            ) == $currentMonth && in_array(
                Carbon::parse($unLeaveDate)->format('l'),
                json_decode($weekend->weekend)
            ) == false) {
                $unApproved[] = $unLeaveDate;
            }
        }
        $employeeUnApprovedLeaves[$employee->id] = count($unApproved);

        /*Approved Leaves*/
        $approvedLeaveDate = [];
        $approvedPeriods = [];
        $approvedLeaves = Leave::where('employee_id', $employee->id)->where(
            'status',
            'Approved'
        )->whereRaw('MONTH(datefrom) = ?', $currentMonth)->whereRaw('YEAR(datefrom) = ?', $currentYear)->get();

        foreach ($approvedLeaves as $approvedLeave) {
            $approvedPeriods[] = CarbonPeriod::create($approvedLeave->datefrom, $approvedLeave->dateto);
        }
        foreach ($approvedPeriods as $approvedPeriod) {
            foreach ($approvedPeriod as $approvedDates) {
                $approvedLeaveDate[] = $approvedDates->format('Y-m-d');
            }
        }

        $Approved = [];
        foreach ($approvedLeaveDate as $leaveDate) {
            if (date(
                'm',
                strtotime($leaveDate)
            ) == $currentMonth && in_array(
                Carbon::parse($leaveDate)->format('l'),
                json_decode($weekend->weekend)
            ) == false) {
                $Approved[] = $leaveDate;
            }
        }
        $employeeApprovedLeaves[$employee->id] = count($Approved);


        /////////
        $numberOfDays = cal_days_in_month(CAL_GREGORIAN, $currentMonth, Carbon::parse($id)->year);
        $workingDays = 0;
        $mothDays = 0;
        for ($i = 1; $i <= $numberOfDays; $i++) {
            $date = Carbon::parse($i . "-" . $currentMonth . "-" . Carbon::parse($id)->year)->toDateString();
            $mothDays += 1;
            if (in_array(Carbon::parse($date)->format('l'), json_decode($weekend->weekend)) == false) {
                $workingDays += 1;
            }
        }

        /////Absents
        $absent = [];
        for ($i = 1; $i <= $mothDays; $i++) {
            $date = Carbon::parse($i . "-" . $currentMonth . "-" . Carbon::parse($id)->format('Y'))->toDateString();
            if (!in_array($date, $presentDate) && in_array(
                Carbon::parse($date)->format('l'),
                json_decode($weekend->weekend)
            ) == false && in_array(
                Carbon::parse($date)->toDateString(),
                $Approved
            ) == false && in_array(Carbon::parse($date)->toDateString(), $unApproved) == false) {
                $absent[] = "";
            }
        }
        $allowanceList = [];
        $addAllowance = 0;
        $addDeduction = 0;
        $template = SalaryTemplate::where('id', $employee->salary_template)->first();
        if (isset($template)) {
            $allowances = Allowance::where('template_id', $employee->salary_template)->get();
            $deductions = Deduction::where('template_id', $employee->salary_template)->get();
            if (isset($allowances)) {
                foreach ($allowances as $allowance) {
                    if ($allowance->type == 1) {
                        $amount1 = $allowance->amount;
                        $allowanceList[$allowance->allowance_name] = $allowance->amount;
                    } else {
                        $amount1 = $template->basic_salary * ($allowance->amount / 100);
                        $allowanceList[$allowance->allowance_name] = $amount1;
                    }
                    $addAllowance = $addAllowance + $amount1;
                }
            } else {
                $addAllowance = 0;
            }
            $deductionList = [];
            if (isset($deductions)) {
                foreach ($deductions as $deduction) {
                    if ($deduction->type == 1) {
                        $amount2 = $deduction->amount;
                        $deductionList[$deduction->deduction_name] = $deduction->amount;
                    } else {
                        $amount2 = $template->basic_salary * ($deduction->amount / 100);
                        $deductionList[$deduction->deduction_name] = $amount2;
                    }
                    $addDeduction = $addDeduction + $amount2;
                }
            } else {
                $addDeduction = 0;
            }
        } else {
            $addAllowance = 0;
            $addDeduction = 0;
        }
        if ($template == null) {
            Session::flash('error', trans('language.Please Assign Salary Template to This Employee'));
            return redirect()->back()->with('locale', $locale);
        } else {
            $AbsentCount[$employee->id] = count($absent);
            $approvedCount[$employee->id] = $employeeApprovedLeaves[$employee->id];
            $unApprovedCount[$employee->id] = $employeeUnApprovedLeaves[$employee->id];
            $absentDeduction[$employee->id] = ($template->basic_salary / $workingDays) * $AbsentCount[$employee->id];
            if ($template->deduction == 1) {
                $absentDeduction[$employee->id] = ($employee->basic_salary / $workingDays) * $AbsentCount[$employee->id];
            } else {
                $absentDeduction[$employee->id] = 0;
            }
            $netPayables[$employee->id] = round(($template->basic_salary - $absentDeduction[$employee->id]) + ($employee->bonus) + ($addAllowance) - ($addDeduction));
            return view('admin.salary.salaryslip')->with('month', $id)->with('employee', $employee)->with(
                'ApprovedCount',
                $approvedCount
            )->with('unApprovedCount', $unApprovedCount)->with(
                'netPayables',
                $netPayables
            )->with('AbsentCounts', $AbsentCount)->with('presents', $present)
                ->with('deductionList', $deductionList)->with('allowanceList', $allowanceList)->with(
                    'basic_salary',
                    $template->basic_salary
                )->with('addAllowance', $addAllowance)->with('addDeduction', $addDeduction)
                ->with('absentDeduction', $absentDeduction)
                ->with('locale', $locale);
        }
    }

    /**
     * Show Form For Generate Personal Salary Slip
     * @param Request $request
     * @return Factory|View
     */
    public function generatePersonalSalarySlip(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $employee = Employee::where('id', Auth::user()->id)->first();
        return view('admin.salary.generatepersonalslip')->with('employee', $employee)->with('locale', $locale);
    }
}
