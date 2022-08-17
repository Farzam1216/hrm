<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Employee\Models\Employee;
use App\Domain\Employee\Models\EmployeeLeaveType;
use App\Domain\Employee\Models\Location;
use App\Domain\Employee\Models\OrganizationHierarchy;
use App\Domain\TimeOff\Models\Leave;
use App\Domain\TimeOff\Models\LeaveType;
use App\Http\Requests\storeAdminLeave;
use App\Http\Requests\validateLeave;
use App\Models\Attendance\AttendanceSummary;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Session;

class LeaveController extends Controller
{
    public $leave_types = [
        'unpaid_leave' => 'Unpaid Leave',
        'half_leave' => 'Half Leave',
        'short_leave' => 'Short Leave',
        'paid_leave' => 'Paid Leave',
        'sick_leave' => 'Sick Leave',
        'casual_leave' => 'Casual Leave',
    ];

    public $statuses = [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'declined' => 'Declined',
    ];

    /**
     * Show All Leave Of Auth User.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function index(Request $request)
    {
        $employee = Auth::User();
        $leaves = Leave::where('employee_id', $employee->id)->with('leaveType')->get();

        $consumed_leaves = 0;
        if ($leaves->count() > 0) {
            foreach ($leaves as $leave) {
                $datefrom = Carbon::parse($leave->datefrom);
                $dateto = Carbon::parse($leave->dateto);
                $consumed_leaves += $dateto->diffInDays($datefrom) + 1;
            }
        }
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        return view('admin.leaves.showleaves', [
            'leaves' => $leaves,
            'consumed_leaves' => $consumed_leaves,
            'employee' => $employee,
            'locale' => $locale,
        ]);
    }

    /**
     * Show List Of All Leaves.
     *
     * @param $lang
     * @param string  $id
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function employeeLeaves($lang, $id = '', Request $request)
    {
        $user = Auth::user()->designation;
        if ($id == 'Approved' || $id == 'Declined' || strtolower($id) == 'pending') {
            $leaves = Leave::leftJoin('employees', function ($join) {
                $join->on('employees.id', '=', 'leaves.employee_id');
            })->where('leaves.status', $id)->orderBy('leaves.datefrom', 'desc');
        } else {
            $leaves = Leave::leftJoin('employees', function ($join) use ($user) {
                $join->on('employees.id', '=', 'leaves.employee_id');
            })->orderBy('leaves.datefrom', 'desc');
        }
        $leaves = $leaves->with('leaveType')->get([
            'employees.*',
            'leaves.id AS leave_id',
            'leaves.leave_type AS leave_type',
            'leaves.datefrom AS datefrom',
            'leaves.dateto AS dateto',
            'leaves.subject AS leave_subject',
            'leaves.line_manager AS line_manager',
            'leaves.point_of_contact AS point_of_contact',
            'leaves.status AS leave_status',
            'leaves.id AS leave_id',
        ]);
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        return view('admin.leaves.employeeleaves', [
            'employees' => $leaves,
        ])->with('id', $id)->with('locale', $locale);
    }

    /**
     * Show Form For Applying For A leave.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $id = Auth::User()->id;
        $OrganizationHierarchy = OrganizationHierarchy::where('employee_id', $id)->with('lineManager')->first();
        $employees = Employee::all();
        $line_manager = isset($OrganizationHierarchy->lineManager) ? $OrganizationHierarchy->lineManager : '';

        return view('admin.leaves.create', [
            'employees' => $employees,
            'locale' => $locale,
            'line_manager' => $line_manager,
            'leave_types' => LeaveType::where('status', '1')->get(),
        ]);
    }

    /**
     * Show Admin For For  Create Leave for Employee.
     *
     * @param $lang
     * @param string  $id
     * @param Request $request
     *
     * @return Response
     */
    public function adminCreate($lang, $id = '', Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        if ($id != '') {
            $employee_id = $id;
        } else {
            $employee_id = Auth::user()->id;
        }
        $OrganizationHierarchy = OrganizationHierarchy::where(
            'employee_id',
            $employee_id
        )->with('lineManager')->first();
        $employees = Employee::where('status', '!=', '0')->orderBy('firstname')->get();
        $selectedEmployee = Employee::where('id', $employee_id)->first();
        $line_manager = isset($OrganizationHierarchy->lineManager) ? $OrganizationHierarchy->lineManager : '';

        return view('admin.leaves.admincreateleave', [
            'employees' => $employees,
            'line_manager' => $line_manager,
            'leave_types' => LeaveType::where('status', '1')->get(),
            'selectedEmployee' => $selectedEmployee,
            'locale' => $locale,
        ]);
    }

    /**
     * Admin Store Leave for Employee.
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws ValidationException
     */
    public function adminStore(storeAdminLeave $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $employee_id = $request->employee;
        $leave_type = $request->leave_type;

        $dateFromTime = Carbon::parse($request->datefrom);
        $dateToTime = Carbon::parse($request->dateto);

        $consumed_leaves = $dateToTime->diffInDays($dateFromTime) + 1;

        $attendance_summaries = AttendanceSummary::where(['employee_id' => $employee_id])
            ->whereDate('date', '>=', $dateFromTime->toDateString())
            ->whereDate('date', '<=', $dateToTime->toDateString())
            ->get();

        if ($attendance_summaries->count() > 0) {
            $msg = '';
            foreach ($attendance_summaries as $key => $attendance_summary) {
                $msg .= ' '.$attendance_summary->date;
            }
            Session::flash('error', trans('language.Employee was already present on dates: ').$msg);

            return redirect()->back()->with('locale', $locale);
        }

        $leave = Leave::create([
            'employee_id' => $employee_id,
            'leave_type' => $leave_type,
            'datefrom' => $dateFromTime,
            'dateto' => $dateToTime,
            'subject' => $request->subject,
            'description' => $request->description,
            'point_of_contact' => $request->point_of_contact,
            'line_manager' => $request->line_manager,
            'cc_to' => $request->cc_to,
            'status' => $request->status,
        ]);
        if ($leave) {
            Session::flash('success', trans('language.Leave for Employee is created successfully'));

            return redirect($locale.'/employee-leaves')->with('locale', $locale);
        }
    }

    /**
     * Employee Store Apply Leave.
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws ValidationException
     */
    public function store(validateLeave $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $employee_id = Auth::User()->id;
        $leave_type = $request->leave_type;

        $dateFromTime = Carbon::parse($request->datefrom);
        $dateToTime = Carbon::parse($request->dateto);

        $consumed_leaves = $dateToTime->diffInDays($dateFromTime) + 1;

        $attendance_summaries = AttendanceSummary::where(['employee_id' => $employee_id])
            ->whereDate('date', '>=', $dateFromTime->toDateString())
            ->whereDate('date', '<=', $dateToTime->toDateString())
            ->get();

        if ($attendance_summaries->count() > 0) {
            $msg = '';
            foreach ($attendance_summaries as $key => $attendance_summary) {
                $msg .= ' '.$attendance_summary->date;
            }
            Session::flash('error', trans('language.Employee was already present on dates: ').$msg);

            return redirect()->back()->with('locale', $locale);
        }

        $leave = Leave::create([
            'employee_id' => $employee_id,
            'leave_type' => $leave_type,
            'datefrom' => $dateFromTime,
            'dateto' => $dateToTime,
            'subject' => $request->subject,
            'description' => $request->description,
            'point_of_contact' => $request->point_of_contact,
            'line_manager' => $request->line_manager,
            'cc_to' => $request->cc_to,
            'status' => 'pending',
        ]);

        if ($leave) {
            Session::flash('success', trans('language.Leave is created succesfully'));

            return redirect($locale.'/my-leaves')->with('locale', $locale);
        }
    }

    /**
     * Display the Leave Details Of Specific ID.
     *
     * @param $lang
     * @param $id
     * @param Request $request
     *
     * @return Response
     */
    public function show($lang, $id, Request $request)
    {
        $leave = Leave::where(['id' => $id])->with([
            // $leave = Leave::find($id)->with([
            'employee',
            'lineManager',
            'pointOfContact',
            'leaveType',
        ])->first();

        $dateFromTime = Carbon::parse($leave->datefrom);
        $dateToTime = Carbon::parse($leave->dateto);
        $leave_days = $dateToTime->diffInDays($dateFromTime) + 1;
        $period = CarbonPeriod::create($dateFromTime, $dateToTime);

        $location_id = $leave->employee->location_id;

        $locations = Location::find($location_id);

        // Iterate over the period
        foreach ($period as $date) {
            if (strstr($locations->address, 'Islamabad')) { //if islamabad offc sat off
                if ($date->format('l') == 'Saturday') {
                    --$leave_days;
                }
            }
            if ($date->format('l') == 'Sunday') {
                --$leave_days;
            }
        }
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        return view('admin.leaves.show', [
            'leave' => $leave,
            'leave_days' => $leave_days,
            'locale' => $locale,
        ]);
    }

    /**
     * Show the form for editing the specified Leave If Not Approved.
     *
     * @param $lang
     * @param $id
     * @param Request $request
     *
     * @return Response
     */
    public function edit($lang, $id, Request $request)
    {
        $employee_id = Auth::User()->id;
        $OrganizationHierarchy = OrganizationHierarchy::where(
            'employee_id',
            $employee_id
        )->with('lineManager')->first();
        $line_manager = isset($OrganizationHierarchy->lineManager) ? $OrganizationHierarchy->lineManager : '';

        $employees = Employee::all();

        $leave = Leave::find($id);
        // $this->sendEmail($leave);
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        return view('admin.leaves.edit', [
            'employees' => $employees,
            'line_manager' => $line_manager,
            'leave_types' => LeaveType::all(),
            'leave' => $leave,
            'locale' => $locale,
        ]);
    }

    /**
     * Update the specified Leave in storage.
     *
     * @param Request $request
     * @param $id
     *
     * @return Response
     *
     * @throws ValidationException
     */
    public function update(validateLeave $request, $lang, $id)
    {
        $leave = Leave::find($id);

        $dateFromTime = Carbon::parse($request->datefrom);
        $dateToTime = Carbon::parse($request->dateto);

        $consumed_leaves = $dateToTime->diffInDays($dateFromTime) + 1;

        $attendance_summaries = AttendanceSummary::where(['employee_id' => $leave->employee_id])
            ->whereDate('date', '>=', $dateFromTime->toDateString())
            ->whereDate('date', '<=', $dateToTime->toDateString())
            ->get();

        if ($attendance_summaries->count() > 0) {
            $msg = '';
            foreach ($attendance_summaries as $key => $attendance_summary) {
                $msg .= ' '.$attendance_summary->date;
            }

            return redirect()->back()->with('error', 'Employee was already present on dates: '.$msg);
        }

        $leave->leave_type = $request->leave_type;
        $leave->datefrom = $dateFromTime;
        $leave->dateto = $dateToTime;
        $leave->subject = $request->subject;
        $leave->description = $request->description;
        $leave->line_manager = $request->line_manager;
        $leave->point_of_contact = $request->point_of_contact;
        $leave->cc_to = $request->cc_to;
        $leave->status = 'Pending';

        $leave = $leave->save();
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        Session::flash('success', trans('language.Leave is created succesfully'));

        return redirect($locale.'/my-leaves')->with('locale', $locale);
    }

    /**
     * Update the The Status Of Leave Of Specific Resource.
     *
     * @param $lang
     * @param $id
     * @param $status
     * @param Request $request
     *
     * @return Response
     */
    public function updateStatus($lang, $id, $status, Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $leave = Leave::find($id);
        if ($leave->status == 'Approved') { // if already approved do nothing
            return redirect()->back()->with('success', trans('language.Leave already approved'))->with('locale', $locale);
        }
        if ($status == 'Approved') {
            $dateFromTime = Carbon::parse($leave->datefrom);
            $dateToTime = Carbon::parse($leave->dateto);

            $consumed_leaves = $dateToTime->diffInDays($dateFromTime) + 1;

            $employee_leave_type = EmployeeLeaveType::where([
                'employee_id' => $leave->employee_id,
                'leave_type_id' => $leave->leave_type,
            ])->first();

            $cnt = $employee_leave_type->count -= $consumed_leaves;

            DB::statement("UPDATE employee_leave_type SET count = $cnt where employee_id = ".$leave->employee_id.' AND leave_type_id = '.$leave->leave_type);
        }

        $leave->status = $status;
        $leave->save();
        Session::flash('success', trans('language.Leave status is updated successfully'));

        return redirect()->back()->with('locale', $locale);
    }

    /**
     * Remove The Specified Leave From Storage.
     *
     * @param Leave $leave
     * @param $lang
     * @param $id
     * @param Request $request
     *
     * @return Response
     */
    public function destroy(Leave $leave, $lang, $id, Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $leave = Leave::where('employee_id', $id)->first();
        $leave->delete();
        Session::flash('success', trans('language.Leave is deleted successfully'));

        return redirect()->back()->with('locale', $locale);
    }

    /**
     * Auth User Delete The Leave From Storage If Not Approved OF Status Not Changes.
     *
     * @param $lang
     * @param $id
     * @param Request $request
     *
     * @return Response
     */
    public function leaveDelete($lang, $id, Request $request)
    {
        $leave = Leave::where('id', $id)->first();
        $leave->delete();
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        Session::flash('success', trans('language.Leave is deleted successfully'));

        return redirect()->back()->with('locale', $locale);
    }
}
