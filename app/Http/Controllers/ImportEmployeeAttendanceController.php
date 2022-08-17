<?php

namespace App\Http\Controllers;

use App\Domain\Attendance\Actions\CreateTemporaryImportAttendanceTable;
use App\Domain\Attendance\Actions\NewTemporaryImportEmployeeAttendance;
use App\Domain\Attendance\Models\ImportEmployeeAttendance;
use App\Domain\Attendance\Actions\ImportBulkEmployeesAttendance;
use App\Imports\AttendanceImport;
use Illuminate\Http\Request;
use App;
use Maatwebsite\Excel\Facades\Excel;
use Session;

class ImportEmployeeAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $locale, $employeeId)
    {

        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $breadcrumbs = [
            ['name' => "Employees"], ['name' => 'Attendance Import']
        ];

        return view(
            'admin.employee-attendance.import_attendance',
            [
                'breadcrumbs' => $breadcrumbs,
                'locale' => $locale,
                'employeeId' => $employeeId,
            ]
        );
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
        $employeeId = $request->employeeId;

        $data = (new ImportBulkEmployeesAttendance)->execute($request);

        if (isset($data['workSchedule'])) {
            Session::flash('error', trans('Work schedule is not assigned to employee at row # ' . $data['row_id'] . ' of excel sheet. Please correct the mentioned row and try again.'));
            return redirect($locale . '/employees/' . $employeeId.'/employee-attendance')->with('locale', $locale);
        }

        if (isset($data['employee'])) {
            Session::flash('error', trans('Employee not found at row # ' . $data['row_id'] . ' of excel sheet. Please correct the mentioned row and try again.'));
            return redirect($locale . '/attendance-management')->with('locale', $locale);
        }

        if ($data['check'] == true) {
            Session::flash('success', trans('language.Employees attendance imported successfully'));
            return redirect($locale . '/employees/' . $employeeId.'/employee-attendance')->with('locale', $locale);
        } else {
            Session::flash('error', trans('Above errors occured against row # ' . $data['row_id'] . ' of excel sheet. Please correct the mentioned row and try again.'));
            return redirect($locale . '/attendance/import/create/' . $employeeId)->with('locale', $locale)
                ->withErrors($data['validator']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($lang, Request $request, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        //        $breadcrumbs = [
        //            ['link' => "javascript:void(0)", 'name' => "People Management"], ['link' => "{$locale}/employees", 'name' => "Employees"], ['link' => route('import.employee.create',[$locale]), 'name' => "Import"], ['name' => "Preview"]
        //        ];


        $createTable = (new CreateTemporaryImportAttendanceTable)->execute();
        $requestData = Excel::toArray(new AttendanceImport(), $request->file('file'));
        $countdata = count($requestData[0]);
        if ($countdata > 0) {
            $data = array_slice($requestData[0], 0, );
        }
        $db_fields = [
            'date',
            'time_in',
            'time_out',
            'reason_for_leaving',
            'status',
        ];
        $importEmployeeAttendance = Excel::import(new AttendanceImport, request()->file('file'));
        return view('admin.employee-attendance.import_preview')
            ->with('locale', $locale)
            ->with('employeeId', $request->employeeId)
            ->with('data', $data)
            ->with('db_fields', $db_fields)
            //            ->with('breadcrumbs', $breadcrumbs)
            ->with('employeesAttendance', ImportEmployeeAttendance::all());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
    public function destroy($id)
    {
        //
    }
}
