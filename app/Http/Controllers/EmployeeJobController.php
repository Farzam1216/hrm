<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Employee\Actions\DeleteEmployeeJobInformation;
use App\Domain\Employee\Actions\StoreJobInformationForEmployee;
use App\Domain\Employee\Actions\storeEmployeeNewJobNotification;
use App\Domain\Employee\Actions\storeEmployeeJobUpdateNotification;
use App\Domain\Employee\Actions\storeEmployeeJobDeleteNotification;
use App\Domain\Employee\Actions\UpdateEmployeeJobInformation;
use App\Http\Requests\validateEmployeeJob;
use Illuminate\Http\Request;
use Session;

class EmployeeJobController extends Controller
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
    public function store( ValidateEmployeeJob $request, $lang, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new StoreJobInformationForEmployee())->execute($request, $id);
        (new storeEmployeeNewJobNotification())->execute($request, $id);
        Session::flash('success', trans('language.Employee Job is created succesfully'));
        return redirect($locale . '/employee/edit/' . $id)->with('locale', $locale);
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
    public function update(ValidateEmployeeJob $request, $lang, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new storeEmployeeJobUpdateNotification())->execute($request, $id);
        (new UpdateEmployeeJobInformation())->execute($request, $id); 
        Session::flash('success', trans('language.Employee Job Information is updated succesfully'));
        return redirect($locale . '/employee/edit/' . $id)->with('locale', $locale);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $lang, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new storeEmployeeJobDeleteNotification())->execute($request, $id); 
        (new DeleteEmployeeJobInformation())->execute($request);
        Session::flash('success', trans('language.Employee Job Information is updated succesfully'));
        return redirect($locale . '/employee/edit/' . $id)->with('locale', $locale);
    }
}
