<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Carbon\Carbon;
use App\Domain\Attendance\Actions\getFiltersData;
use App\Domain\Attendance\Actions\getFilteredAttendance;
use App\Domain\Attendance\Actions\CreateTemporaryImportAttendanceTable;
use App\Imports\AttendanceImport;
use App\Domain\Attendance\Models\ImportEmployeeAttendance;
use App\Domain\Attendance\Actions\ImportBulkEmployeesAttendance;
use App\Domain\Attendance\Actions\UpdateEmployeeAttendance;
use App\Domain\Attendance\Actions\GetEmployeeAttendanceById;
use App\Domain\Attendance\Actions\GetEmployeeAttendanceHistoryById;
use App\Domain\Attendance\Models\EmployeeAttendance;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;

class AttendanceManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $locale, $id = '')
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $data = (new getFiltersData())->execute($id);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Attendance Management"], ['name' => "Employees Attendance"]
        ];
        return view('admin.attendance_management.index'
        ,[
            'breadcrumbs' => $breadcrumbs,
            'locale'=> $locale,
            'employees' => $data['employees'],
            'months' => $data['months'],
            'years' => $data['years']
        ]);
    }


    public function create(Request $request, $locale, $id = '')
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Attendance Management"], ['link' => route('attendance-management.index', [$locale]), 'name' => "Employees Attendance"], ['name' => "Import"]
        ];
        return view('admin.attendance_management.create'
        ,[
            'breadcrumbs' => $breadcrumbs,
            'locale'=> $locale,       
        ]);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function preview(Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Attendance Management"], ['link' => route('attendance-management.index', [$locale]), 'name' => "Employees Attendance"], ['link' => route('attendance-management.create', [$locale]), 'name' => "Import"], ['name' => "Preview"]
        ];
        $createTable = (new CreateTemporaryImportAttendanceTable)->execute();
        $requestData = Excel::toArray(new AttendanceImport(), $request->file('file'));
            $countdata = count($requestData[0]);
            if ($countdata > 0) {
                $data = array_slice($requestData[0], 0, 6);
            }
        $db_fields = [
            'employee_number',
            'date',
            'time_in',
            'time_out',
            'reason_for_leaving',
            'status',
        ];
        $ImportEmployeeAttendance = Excel::import(new AttendanceImport(), $request->file('file'));
        return view('admin.attendance_management.import_preview')
            ->with('locale', $locale)
            ->with('breadcrumbs', $breadcrumbs)
            ->with('data', $data)
            ->with('db_fields', $db_fields)
            ->with('employeesAttendance', ImportEmployeeAttendance::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $data = (new ImportBulkEmployeesAttendance)->execute($request);
        if (isset($data['employee_no'])) {
            Session::flash('error', trans('Employee number is required at row # ' . $data['row_id'] . ' of excel sheet. Please correct the mentioned row and try again.'));
            return redirect($locale . '/attendance-management')->with('locale', $locale);
        }

        if (isset($data['employee'])) {
            Session::flash('error', trans('Employee not found at row # ' . $data['row_id'] . ' of excel sheet. Please correct the mentioned row and try again.'));
            return redirect($locale . '/attendance-management')->with('locale', $locale);
        }
        
        if (isset($data['workSchedule'])) {
            Session::flash('error', trans('Work schedule is not assigned to employee at row # ' . $data['row_id'] . ' of excel sheet. Please correct the mentioned row and try again.'));
            return redirect($locale . '/attendance-management')->with('locale', $locale);
        }

        if ($data['check'] == true) {
            Session::flash('success', trans('language.Employees attendance imported successfully'));
            return redirect($locale . '/attendance-management')->with('locale', $locale);
        } else {
            Session::flash('error', trans('Above errors occured against row # ' . $data['row_id'] . ' of excel sheet. Please correct the mentioned row and try again.'));
            return redirect($locale . '/attendance-management/')->with('locale', $locale)
                ->withErrors($data['validator']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
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
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Attendance Management"], ['link' => route('attendance-management.index', [$locale]), 'name' => "Employees Attendance"], ['name' => "Edit"]
        ];

        $employeeAttendance = (new GetEmployeeAttendanceById)->execute($id);

        return view('admin.attendance_management.edit')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs)
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
            return redirect($locale . '/attendance-management')->with('locale', $locale);
        } else {
            Session::flash('error', trans('language.Something went wrong while updating employee attendance'));
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getAttendance(Request $request)
    {
        $employeeAttendance = (new getFilteredAttendance)->execute($request);

        return response()->json($employeeAttendance);
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

    /**
     * Show history of the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function history(Request $request, $locale, $id)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Attendance Management"], ['link' => route('attendance-management.index', [$locale]), 'name' => "Employees Attendance"], ['name' => "History"]
        ];

        $employeeAttendance = (new GetEmployeeAttendanceHistoryById)->execute($id);

        return view('admin.attendance_management.history')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs)
        ->with('employeeAttendance', $employeeAttendance);
    }

    /**
     * Validate Work Schedule With selected selected time.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function validateWorkSchedule(Request $request)
    {
        $schedule_start_time = Carbon::parse($request->schedule_start_time);
        $schedule_flex_time = Carbon::parse($request->schedule_flex_time);
        $schedule_end_time = Carbon::parse($request->schedule_end_time);
        $time_in = Carbon::parse($request->time_in);
        $time_out = Carbon::parse($request->time_out);
        if ($time_in->gte($schedule_start_time) && $time_out->lte($schedule_end_time)) {
            return response()->json(true);
        }
        if ($time_in->lt($schedule_start_time) || $time_in->gt($schedule_end_time) || $time_out->lt($schedule_start_time) || $time_out->lt($schedule_end_time)) {
            return response()->json(false);
        }
    }
}
