<?php

namespace App\Http\Controllers;

use App;
use Session;
use Illuminate\Http\Request;
use App\Domain\Compensation\Actions\StoreCompensation;
use App\Domain\Compensation\Actions\UpdateCompensationById;
use App\Domain\Compensation\Actions\DestroyCompensationById;
use App\Http\Requests\storeCompensation as storeCompensationValidation;
use App\Http\Requests\updateCompensation as updateCompensationValidation;

class EmployeeCompensationController extends Controller
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
    public function store(storeCompensationValidation $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $data = (new StoreCompensation)->execute($request);

        if ($data) {
            Session::flash('success', trans('language.Compensation is stored successfully'));

            return redirect()->route('employee.edit', [$locale, $request->employee_id]);
        } else {
            Session::flash('error', trans('language.Something went worng while storing compensation'));

            return redirect()->back()->withInput($request->all());
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
    public function update(updateCompensationValidation $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $data = (new UpdateCompensationById)->execute($request);

        if ($data) {
            Session::flash('success', trans('language.Compensation is updated successfully'));

            return redirect()->route('employee.edit', [$locale, $request->employee_id]);
        } else {
            Session::flash('error', trans('language.Something went worng while updating compensation'));

            return redirect()->back()->withInput($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $data = (new DestroyCompensationById)->execute($request);

        if ($data) {
            Session::flash('success', trans('language.Compensation is deleted successfully'));
        } else {
            Session::flash('error', trans('language.Something went worng while deleting compensation'));
        }

        return redirect()->route('employee.edit', [$locale, $request->employee_id]);
    }
}
