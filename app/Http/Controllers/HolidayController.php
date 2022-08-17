<?php

namespace App\Http\Controllers;

use App;
use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\storeHoliday as storeHolidayValidation;
use App\Domain\Holiday\Models\Holiday;
use App\Domain\Holiday\Actions\GetHolidays;
use App\Domain\Holiday\Actions\StoreHoliday;
use App\Domain\Holiday\Actions\UpdateHoliday;
use App\Domain\Holiday\Actions\DestroyHolidayById;
use App\Domain\Holiday\Actions\GetDetailsToEditHoliday;
use App\Domain\Holiday\Actions\GetDetailsToCreateHoliday;

class HolidayController extends Controller
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
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['name' => "Holidays"]
        ];

        $data = (new GetHolidays)->execute();

        return view('admin.holidays.index')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs)
        ->with('employee', $data['employee'])
        ->with('holidays', $data['holidays'])
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
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['link' => route('holidays.index', [$locale]), 'name' => "Holidays"], ['name' => "Add"]
        ];

        $data = (new GetDetailsToCreateHoliday)->execute();

        return view('admin.holidays.create')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs)
        ->with('divisions', $data['divisions'])
        ->with('locations', $data['locations'])
        ->with('departments', $data['departments'])
        ->with('designations', $data['designations'])
        ->with('employment_statuses', $data['employment_statuses']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(storeHolidayValidation $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $data = (new StoreHoliday)->execute($request);

        if ($data) {
            Session::flash('success', trans('language.Holiday is stored successfully'));
        } else {
            Session::flash('error', trans('language.Something went worng while storing holiday'));
        }

        return redirect()->route('holidays.index', [$locale]);
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
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['link' => route('holidays.index', [$locale]), 'name' => "Holidays"], ['name' => "Edit"]
        ];

        $data = (new GetDetailsToEditHoliday)->execute($id);

        return view('admin.holidays.edit')
        ->with('locale', $locale)
        ->with('breadcrumbs', $breadcrumbs)
        ->with('holiday', $data['holiday'])
        ->with('divisions', $data['divisions'])
        ->with('locations', $data['locations'])
        ->with('departments', $data['departments'])
        ->with('designations', $data['designations'])
        ->with('employment_statuses', $data['employment_statuses']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $locale, $id)
    {
        $request->validate ([
            'holiday_name' => 'required|unique:holidays,name,'.$id
        ]);

        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $data = (new UpdateHoliday)->execute($request, $id);

        if ($data) {
            Session::flash('success', trans('language.Holiday is updated successfully'));
        } else {
            Session::flash('error', trans('language.Something went worng while updating holiday'));
        }

        return redirect()->route('holidays.index', [$locale]);
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

        $data = (new DestroyHolidayById)->execute($id);

        if ($data) {
            Session::flash('success', trans('language.Holiday is deleted successfully'));
        } else {
            Session::flash('error', trans('language.Something went worng while deleting holiday'));
        }
        

        return redirect()->route('holidays.index', [$locale]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function filterHolidays(Request $request)
    {
        $date = Carbon::parse($request->date);
        $holidays = Holiday::where('date', 'LIKE', $date->year.'%')->get();
        return $holidays;
    }
}
