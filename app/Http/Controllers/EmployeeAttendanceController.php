<?php

namespace App\Http\Controllers;

use App\Domain\Attendance\Actions\IsAttendanceApprovalValid;
use App\Domain\Attendance\Actions\EmployeeClockOutisValid;
use App\Domain\Attendance\Actions\GetAllEmployeeAttendance;
use App\Domain\Attendance\Actions\GetCurrentMonthEmployeeAttendance;
use App\Domain\Attendance\Actions\GetEmployeeWorkedHours;
use App\Domain\Attendance\Actions\GetEmployeeWorkSchedule;
use App\Domain\Attendance\Actions\StoreEmployeeAttendance;
use App\Domain\Attendance\Actions\UserHasApprovalPermission;
use App\Domain\Employee\Actions\AuthorizeUser;
use App\Domain\Employee\Actions\GetEmployeeByID;
use App\Domain\Employee\Actions\ToggleEmployeeBasedMenuItems;
use App\Domain\Attendance\Actions\GetAttendanceByEmployeeId;
use App\Domain\Attendance\Actions\GetEmployeeAttendanceById;
use App\Domain\Attendance\Actions\GetEmployeeAttendanceHistoryById;
use App\Domain\Attendance\Actions\UpdateEmployeeAttendance;
use App\Domain\Employee\Actions\GetAllEmployeesWithJobDetails;
use App\Domain\Employee\Actions\GetAuthorizedUserPermissions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Auth;
use Session;

class EmployeeAttendanceController extends Controller
{
    public function index($lang, Request $request, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $request->id = $id;

        $data = (new GetCurrentMonthEmployeeAttendance())->execute($id);
        $allAttendance = (new GetAllEmployeeAttendance())->execute($request);
        $employeeWorkedInHours = (new GetEmployeeWorkedHours())->execute($id);
        $workSchedule = (new GetEmployeeWorkSchedule())->execute($id);
        $employeeClockOutValid = (new EmployeeClockOutisValid())->execute($id);
        $employeeAttendanceApprovalIsValid = (new IsAttendanceApprovalValid())->execute($id);
        $hasApprovalPermission = (new UserHasApprovalPermission())->execute();
        $employee = (new GetEmployeeByID())->execute($id);
        if (Auth::user()->id == $id) {
            $request->session()->forget('unauthorized_user');
            $breadcrumbs = [
                ['link' => 'javascript:void(0)', 'name' => "My Info"], ['name' => "My Attendance"]
            ];
            $title = 'My Attendance';
            (new ToggleEmployeeBasedMenuItems())->execute($id);
            //Authorize current User
            (new AuthorizeUser())->execute('edit', $this, 'Employee', [$employee]);

            $data['info'] = (new GetAllEmployeesWithJobDetails())->execute();
            $permissions = (new GetAuthorizedUserPermissions())->execute($data['info']['employee']);
        } else {
            $breadcrumbs = [
                ['link' => $locale."/employees", 'name' => "Employees"], ['name' => $employee->firstname . ' ' . $employee->lastname . ' Attendance']
            ];
            $title = 'Attendance';
            (new ToggleEmployeeBasedMenuItems())->execute($id);
            //Authorize current User
            (new AuthorizeUser())->execute('edit', $this, 'Employee', [$employee]);

            $data['info'] = (new GetAllEmployeesWithJobDetails())->execute();
            $permissions = (new GetAuthorizedUserPermissions())->execute($data['info']['employee']);
        }
        return view('admin.employee-attendance.index',
            [
                'title' => $title,
                'breadcrumbs' => $breadcrumbs,
                'locale' => $locale,
                'employeeID' => $id,
                'permissions' => $permissions,
                'allAttendance' => $allAttendance['attendanceCollections'],
                'employee' => $allAttendance['employee'],
                'employeeAttendance' => $data['employeeAttendance'],
                'employeeWorkedToday' => $employeeWorkedInHours['employeeWorkedToday'],
                'employeeWorkedThisWeek' => $employeeWorkedInHours['employeeWorkedThisWeek'],
                'employeeWorkedThisMonth' => $employeeWorkedInHours['employeeWorkedThisMonth'],
                'employeeAttendanceToday' => $data['employeeAttendanceToday'],
                'employeeAllTodaysAttendanceToday' => $data['employeeAllTodaysAttendanceToday'],
                'startTime' => $workSchedule['schedule_start_time'],
                'countAttendanceToday' => $data['countAttendanceToday'],
                'employeeClockOutValid' => $employeeClockOutValid,
                'endTime' => $workSchedule['schedule_end_time'],
                'employeeAttendanceApprovalIsValid' => $employeeAttendanceApprovalIsValid,
                'hasApprovalPermission' => $hasApprovalPermission,
            ]);
    }

    public function filter(Request $request)
    {
        $allAttendance = (new GetAllEmployeeAttendance())->execute($request);
        return response()->json($allAttendance['attendanceCollections']);
    }

    public function store($lang, Request $request, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $result = (new StoreEmployeeAttendance())->execute($request, $id);

        if ($result == true) {
            Session::flash('success', trans('Attendance Marked Successfully'));
        } else {
            Session::flash('error', trans('Irregular time for attendance.'));
        }
        return redirect($locale . '/employees/' . $id.'/employee-attendance')->with('locale', $locale);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $locale, $employee_id, $date)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $employee = (new GetEmployeeByID())->execute($employee_id);
        if (Auth::user()->id == $employee_id) {
            $request->session()->forget('unauthorized_user');
            $breadcrumbs = [
                ['link' => 'javascript:void(0)', 'name' => "My Info"], ['link' => route('employees.employee-attendance.index', [$locale, $employee_id]), 'name' => "My Attendance"], ['name' => $date]
            ];
            $permissions = false;
        } else {
            $breadcrumbs = [
                ['link' => $locale."/employees", 'name' => "Employees"], ['link' => route('employees.employee-attendance.index', [$locale, $employee_id]), 'name' => $employee->firstname . ' ' . $employee->lastname . ' Attendance'], ['name' => $date]
            ];
            (new ToggleEmployeeBasedMenuItems())->execute($employee_id);
            //Authorize current User
            (new AuthorizeUser())->execute('edit', $this, 'Employee', [$employee]);

            $data['info'] = (new GetAllEmployeesWithJobDetails())->execute();
            $permissions = (new GetAuthorizedUserPermissions())->execute($data['info']['employee']);
        }

        $employee = (new GetAttendanceByEmployeeId())->execute($employee_id);

        return view('admin.employee-attendance.show')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs)
        ->with('permissions', $permissions)
        ->with('date', Carbon::parse($date))
        ->with('employee', $employee);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $locale, $employee_id = '', $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $employeeAttendance = (new GetEmployeeAttendanceById)->execute($id);
        $employee = (new GetEmployeeByID())->execute($employeeAttendance->employee_id);
        $date = $employeeAttendance->created_at->format('Y-m-d');

        if (Auth::user()->id == $employee->id) {
            $request->session()->forget('unauthorized_user');
            $breadcrumbs = [
                ['link' => 'javascript:void(0)', 'name' => "My Info"], ['link' => route('employees.employee-attendance.index', [$locale, $employee_id]), 'name' => "My Attendance"], ['link' => route('employees.employee-attendance.show', [$locale, $employee->id, $date]), 'name' => $date], ['name' => 'Edit']
            ];
        } else {
            $breadcrumbs = [
                ['link' => $locale."/employees", 'name' => "Employees"], ['link' => route('employees.employee-attendance.index', [$locale, $employee->id]), 'name' => $employee->firstname . ' ' . $employee->lastname . ' Attendance'], ['link' => route('employees.employee-attendance.show', [$locale, $employee->id, $date]), 'name' => $date], ['name' => 'Edit']
            ];
            (new ToggleEmployeeBasedMenuItems())->execute($employee->id);
            //Authorize current User
            (new AuthorizeUser())->execute('edit', $this, 'Employee', [$employee]);
        }


        return view('admin.employee-attendance.edit')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs)
        ->with('date', $date)
        ->with('employee', $employee)
        ->with('employeeAttendance', $employeeAttendance);
    }

    /**
     * Update the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $locale, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $data = (new UpdateEmployeeAttendance)->execute($request);

        if ($data['timeCheck'] == false) {
            Session::flash('error', trans('language.Time out must be greater than Time in'));
            return redirect()->back();
        }

        if ($data['employeeAttendance']) {
            Session::flash('success', trans('language.Employee attendance is updated successfully'));

            return redirect($locale . '/employees/'. $data['employeeAttendance']->employee_id . '/employee-attendance')->with('locale', $locale);
        } else {
            Session::flash('error', trans('language.Something went wrong while updating employee attendance'));
            return redirect()->back();
        }
    }


    /**
     * Show history of the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function history(Request $request, $locale, $employee_id = '', $id)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $employeeAttendance = (new GetEmployeeAttendanceHistoryById)->execute($id);
        $employee = (new GetEmployeeByID())->execute($employeeAttendance->employee_id);
        $date = $employeeAttendance->created_at->format('Y-m-d');

        if (Auth::user()->id == $employee->id) {
            $request->session()->forget('unauthorized_user');
            $breadcrumbs = [
                ['link' => 'javascript:void(0)', 'name' => "My Info"], ['link' => route('employees.employee-attendance.index', [$locale, $employee_id]), 'name' => "My Attendance"], ['link' => route('employees.employee-attendance.show', [$locale, $employee->id, $date]), 'name' => $date], ['name' => 'History']
            ];
        } else {
            $breadcrumbs = [
                ['link' => $locale."/employees", 'name' => "Employees"], ['link' => route('employees.employee-attendance.index', [$locale, $employee->id]), 'name' => $employee->firstname . ' ' . $employee->lastname . ' Attendance'], ['link' => route('employees.employee-attendance.show', [$locale, $employee->id, $date]), 'name' => $date], ['name' => 'History']
            ];
            (new ToggleEmployeeBasedMenuItems())->execute($employee->id);
            //Authorize current User
            (new AuthorizeUser())->execute('edit', $this, 'Employee', [$employee]);
        }

        return view('admin.employee-attendance.history')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs)
        ->with('date', $date)
        ->with('employee', $employee)
        ->with('employeeAttendance', $employeeAttendance);
    }
}
