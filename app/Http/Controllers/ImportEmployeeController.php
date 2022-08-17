<?php

namespace App\Http\Controllers;

use App;
use Session;
use Illuminate\Http\Request;
use App\Http\Requests\importFile;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TemporaryImportEmployee;
use App\Domain\Employee\Models\ImportEmployee;
use App\Domain\Employee\Actions\ImportBulkEmployees;
use App\Domain\Employee\Actions\CreateTemporaryImportEmployeeTable;

class ImportEmployeeController extends Controller
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
    public function create(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "People Management"], ['link' => "{$locale}/employees", 'name' => "Employees"], ['name' => "Import"]
        ];

        return view('admin.employees.import_employees',
        [
            'breadcrumbs' => $breadcrumbs,
            'locale' => $locale
        ]);
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

        $data = (new ImportBulkEmployees)->execute($request);
        if (isset($data['official_email'])) {
            Session::flash('error', trans('Official email should be unique at row #  of excel sheet. Please correct the mentioned row and try again.'));
            return redirect()->route('import.employee.create', [$locale]);
        }

        if (isset($data['personal_email'])) {
            Session::flash('error', trans('Personal email should be unique at row #  of excel sheet. Please correct the mentioned row and try again.'));
            return redirect()->route('import.employee.create', [$locale])->withErrors('error', 'Personal email already exist');
        }

        if ($data['check'] == true) {
            Session::flash('success', trans('language.Employees imported successfully'));

            return redirect()->route('employees',[$locale])->with('locale', $locale); 
        } else {
            Session::flash('error', trans('Above errors occured against row #  of excel sheet. Please correct the mentioned row and try again.'));
            return redirect()->route('import.employee.create', [$locale])
            ->withErrors($data['validator']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(importFile $request, $locale)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "People Management"], ['link' => "{$locale}/employees", 'name' => "Employees"], ['link' => route('import.employee.create',[$locale]), 'name' => "Import"], ['name' => "Preview"]
        ];

        $createTable = (new CreateTemporaryImportEmployeeTable)->execute();
        $requestData = Excel::toArray(new TemporaryImportEmployee(), $request->file('file'));
            $countdata = count($requestData[0]);
            if ($countdata > 0) {
                $data = array_slice($requestData[0], 0, 6);
                $data_file = ImportEmployee::create([
                    'excel_data' => json_encode($data)
                ]);
            }
            $db_fields = [
                'employee_no',
                'firstname',
                'lastname',
                'contact_no',
                'official_email',
                'personal_email',
                'nin',
                'gender',
                'marital_status',
                'emergency_contact_relationship',   
                'emergency_contact',                
                'current_address',                 
                'permanent_address',               
                'city',                         
            ];
        // $importEmployees = Excel::import(new TemporaryImportEmployee,request()->file('file'));

        return view('admin.employees.import_preview')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs)
        ->with('data', $data)
        ->with('db_fields', $db_fields)
        ->with('employees', ImportEmployee::all());
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
