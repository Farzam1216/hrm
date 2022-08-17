<?php

namespace App\Http\Controllers;

use App;
use Session;
use Illuminate\Http\Request;
use App\Domain\Compensation\Actions\GetCompensationChangeReasons;
use App\Domain\Compensation\Actions\StoreCompensationChangeReason;
use App\Domain\Compensation\Actions\UpdateCompensationChangeReasonById;
use App\Domain\Compensation\Actions\DestroyCompensationChangeReasonById;
use App\Domain\Compensation\Actions\GetCompensationChangeReasonById;
use App\Http\Requests\storeCompensationChangeReason as storeCompensationChangeReasonValidation;
use App\Http\Requests\updateCompensationChangeReason as updateCompensationChangeReasonValidation;

class CompensationChangeReasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['link' => "javascript:void(0)", 'name' => "Compensation"], ['name' => "Change Reasons"]
        ];

        $data = (new GetCompensationChangeReasons)->execute();

        return view('admin.compensation_change_reason.index')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs)
        ->with('changeReasons', $data['changeReasons'])
        ->with('permissions', $data['permissions']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['link' => "javascript:void(0)", 'name' => "Compensation"], ['link' => route('compensation-change-reasons.index', [$locale]), 'name' => "Change Reasons"], ['name' => "Add"]
        ];

        return view('admin.compensation_change_reason.create')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(storeCompensationChangeReasonValidation $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $data = (new StoreCompensationChangeReason)->execute($request);

        if ($data) {
            Session::flash('success', trans('language.Compensation change reason is stored successfully'));

            return redirect()->route('compensation-change-reasons.index', [$locale]);
        } else {
            Session::flash('error', trans('language.Something went worng while storing compensation change reason'));

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
    public function edit(Request $request, $locale, $id)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['link' => "javascript:void(0)", 'name' => "Compensations"], ['link' => route('compensation-change-reasons.index', [$locale]), 'name' => "Chnage Reasons"], ['name' => "Edit"]
        ];

        $changeReason = (new GetCompensationChangeReasonById)->execute($id);

        return view('admin.compensation_change_reason.edit')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs)
        ->with('changeReason', $changeReason);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(updateCompensationChangeReasonValidation $request, $locale, $id)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $data = (new UpdateCompensationChangeReasonById)->execute($request, $id);

        if ($data) {
            Session::flash('success', trans('language.Compensation change reason is updated successfully'));

            return redirect()->route('compensation-change-reasons.index', [$locale]);
        } else {
            Session::flash('error', trans('language.Something went worng while updating compensation change reason'));

            return redirect()->back()->withInput($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $locale, $id)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $data = (new DestroyCompensationChangeReasonById)->execute($id);

        if ($data) {
            Session::flash('success', trans('language.Compensation change reason is deleted successfully'));
        } else {
            Session::flash('error', trans('language.Something went worng while deleting compensation change reason'));
        }

        return redirect()->route('compensation-change-reasons.index', [$locale]);
    }
}
