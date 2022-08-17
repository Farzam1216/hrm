<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Employee\Actions\DeleteEmployeeEmploymentStatus;
use App\Domain\Employee\Actions\StoreEmployementStatusForEmployee;
use App\Domain\Employee\Actions\UpdateEmployementStatusForEmployee;
use App\Domain\Employee\Actions\storeEmployeeNewEmploymentStatusNotification;
use App\Domain\Employee\Actions\storeEmployeeEmploymentStatusUpdateNotification;
use App\Domain\Employee\Actions\storeEmployeeEmploymentStatusDeleteNotification;
use App\Http\Requests\validateEmployeeEmploymentStatus;
use Illuminate\Http\Request;
use Session;

class EmployeeEmploymentStatusController extends Controller
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
    public function store($lang, Request $request, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new StoreEmployementStatusForEmployee())->execute($request, $id);
        (new storeEmployeeNewEmploymentStatusNotification())->execute($request, $id);
        Session::flash('success', trans('language.Employment status is added succesfully'));
        return redirect($locale . '/employee/edit/' . $id)->with('locale', $locale);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EmployeeEmploymentStatus  $employeeEmploymentStatus
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EmployeeEmploymentStatus  $employeeEmploymentStatus
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EmployeeEmploymentStatus  $employeeEmploymentStatus
     * @return \Illuminate\Http\Response
     */
    public function update($lang, validateEmployeeEmploymentStatus $request, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new storeEmployeeEmploymentStatusUpdateNotification())->execute($request,$id);
        (new UpdateEmployementStatusForEmployee())->execute($request);
        Session::flash('success', trans('language.Employment Status is updated succesfully'));
        return redirect($locale . '/employee/edit/' . $id)->with('locale', $locale);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EmployeeEmploymentStatus  $employeeEmploymentStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy($lang, Request $request, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new storeEmployeeEmploymentStatusDeleteNotification())->execute($request,$id);
        (new DeleteEmployeeEmploymentStatus())->execute($request);
        Session::flash('success', trans('language.Employment Status is deleted succesfully'));
        return redirect($locale . '/employee/edit/' . $id)->with('locale', $locale);
    }
}
