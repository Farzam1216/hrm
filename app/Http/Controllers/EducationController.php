<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Employee\Actions\DeleteEmployeeEducation;
use App\Domain\Employee\Actions\StoreEmployeeEducation;
use App\Domain\Employee\Actions\UpdateEmployeeEducation;
use App\Domain\Employee\Actions\storeEmployeeNewEducationNotification;
use App\Domain\Employee\Actions\storeEmployeeEducationUpdateNotification;
use App\Domain\Employee\Actions\storeEmployeeEducationDeleteNotification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;

class EducationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(Request $request , $id)
    {
        $educationCreated = (new StoreEmployeeEducation())->execute($request, $id);
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $tabname = 'custom-education';
        if ($educationCreated) {
            (new storeEmployeeNewEducationNotification())->execute($request, $id);
            Session::flash('success', trans('language.Education is created successfully'));
        }else{
            Session::flash('error', trans('language.Failed to create Education'));
        }
        return redirect()->back()->with(['tab'=>$tabname]);
    }

    /**
     * Update the specified resource in storage.
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return Response
     */
    public function update($lang,Request $request, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $educationUpdated = (new UpdateEmployeeEducation())->execute($id, $request);
        $tabname = 'custom-education';
        if ($educationUpdated) {
            (new storeEmployeeEducationUpdateNotification())->execute($request,$id);
            Session::flash('success', trans('language.Education is updated successfully'));
        }
        return redirect()->back()->with('tabName', $tabname);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $lang
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function destroy($lang, $id, Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new storeEmployeeEducationDeleteNotification())->execute($request,$id);
        $educationDeleted = (new DeleteEmployeeEducation())->execute($id);
        if ($educationDeleted == null) {
            Session::flash('success', trans('language.Education is deleted successfully'));
        }
        $tabname = 'custom-education';
        return redirect()->back()->with('tabName', $tabname);
    }
}
