<?php

namespace App\Http\Controllers;

use App;
use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Domain\Employee\Actions\AuthorizeUser;
use App\Domain\Employee\Actions\GetEmployeeByID;
use App\Domain\Attendance\Actions\GetCorrectionRequestById;
use App\Domain\Attendance\Actions\GetAllCorrectionRequests;
use App\Domain\Attendance\Actions\StoreAttendanceCorrection;
use App\Domain\Attendance\Actions\GetAttendanceByEmployeeId;
use App\Domain\Employee\Actions\ToggleEmployeeBasedMenuItems;
use App\Domain\Employee\Actions\GetAuthorizedUserPermissions;
use App\Domain\Employee\Actions\GetAllEmployeesWithJobDetails;
use App\Domain\Attendance\Actions\UpdateAttendanceCorrectionById;

class EmployeeAttendanceCorrectionController extends Controller
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
            ['link' => "javascript:void(0)", 'name' => "Attendance Management"],['name' => "Correction Requests"]
        ];

        $attendanceCorrections = (new GetAllCorrectionRequests())->execute();

        $data['info'] = (new GetAllEmployeesWithJobDetails())->execute();
        $permissions = (new GetAuthorizedUserPermissions())->execute($data['info']['employee']);

        return view('admin.employee-attendance-correction.index')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs)
        ->with('permissions', $permissions)
        ->with('attendanceCorrections', $attendanceCorrections);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $locale, $employee_id, $date)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $employee = (new GetEmployeeByID())->execute($employee_id);
        if (Auth::user()->id == $employee_id) {
            $request->session()->forget('unauthorized_user');
            $breadcrumbs = [
                ['link' => 'javascript:void(0)', 'name' => "My Info"], ['link' => route('employees.employee-attendance.index', [$locale, $employee_id]), 'name' => "My Attendance"], ['name' => $date], ['name' => 'Create Correction Request']
            ];
            $permissions = false;
        } else {
            $breadcrumbs = [
                ['link' => $locale."/employees", 'name' => "Employees"], ['link' => route('employees.employee-attendance.index', [$locale, $employee_id]), 'name' => $employee->firstname . ' ' . $employee->lastname . ' Attendance'], ['name' => $date], ['name' => 'Create Correction Request']
            ];
            (new ToggleEmployeeBasedMenuItems())->execute($employee_id);
            //Authorize current User
            (new AuthorizeUser())->execute('edit', $this, 'Employee', [$employee]);

            $data['info'] = (new GetAllEmployeesWithJobDetails())->execute();
            $permissions = (new GetAuthorizedUserPermissions())->execute($data['info']['employee']);
        }

        $employee = (new GetAttendanceByEmployeeId())->execute($employee_id);

        return view('admin.employee-attendance-correction.create')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs)
        ->with('permissions', $permissions)
        ->with('date', Carbon::parse($date))
        ->with('employee', $employee);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $data = (new StoreAttendanceCorrection())->execute($request);

        if ($data == 'processed') {
            Session::flash('success', trans('language.Another attendance correction request against this record is already processed'));
            return redirect()->route('employees.employee-attendance.index', [$locale, $request->employee_id]);
        }

        if ($data == 'exist') {
            Session::flash('success', trans('language.Pending attendance correction request against this record is already in process'));
            return redirect()->route('employees.employee-attendance.index', [$locale, $request->employee_id]);
        }

        if ($data) {
            Session::flash('success', trans('language.Attendance correction request is submitted successfully'));
            return redirect()->route('employees.employee-attendance.index', [$locale, $request->employee_id]);
        }

        if (!$data) {
            Session::flash('error', trans('language.Something went wrong while storing attendance correction request'));
            return redirect()->back();
        }
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
    public function edit(Request $request, $locale, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Attendance Management"], ['link' => route('correction-requests.index', [$locale]), 'name' => "Correction Requests"], ['name' => "Edit"]
        ];

        $correctionRequest = (new GetCorrectionRequestById())->execute($id);

        return view('admin.employee-attendance-correction.edit')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs)
        ->with('correctionRequest', $correctionRequest);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $locale, $id)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $data = (new UpdateAttendanceCorrectionById())->execute($request, $id);

        if ($data) {
            Session::flash('success', trans('language.Attendance correction request is updated successfully'));
            return redirect()->route('correction-requests.index', [$locale]);
        }

        if (!$data) {
            Session::flash('error', trans('language.Something went wrong while updating attendance correction request'));
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
