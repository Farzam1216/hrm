<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use App\Domain\Attendance\Actions\getFiltersData;
use App\Domain\Attendance\Actions\DeleteEmployeeMonthlyAttendance;
use App\Http\Requests\validateDeleteEmployeeMonthlyAttendance;

class DeleteMonthlyAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($lang,Request $request)
    {
        //
        $id = '';
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $data = (new getFiltersData())->execute($id);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['name' => "Delete Monthly Attendance"]
        ];
        return view('admin.attendance_management.deleteMonthlyAttendance'
        ,[
            'breadcrumbs' => $breadcrumbs,
            'locale'=> $locale,
            'employees' => $data['employees'],
            'months' => $data['months'],
            'years' => $data['years']
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($lang , validateDeleteEmployeeMonthlyAttendance $request)
    {
        //
         (new DeleteEmployeeMonthlyAttendance())->execute($request);   
         return redirect($lang . '/attendance-management');
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
