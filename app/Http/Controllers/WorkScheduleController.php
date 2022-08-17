<?php

namespace App\Http\Controllers;

use App\Domain\Attendance\Actions\DeleteWorkSchedule;
use App\Domain\Attendance\Actions\GetAllWorkSchedules;
use App\Domain\Attendance\Actions\GetWorkSchedule;
use App\Domain\Attendance\Actions\StoreWorkSchedule as StoreWorkSchedule;
use App\Domain\Attendance\Actions\UpdateWorkSchedule;
use App\Http\Requests\storeWorkSchedule as StoreWorkScheduleRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App;
use Session;

class WorkScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        //FIXME: $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['name' => "Work Schedules"]
        ];
        $workScedules = (new GetAllWorkSchedules())->execute();
        return view('admin.work_schedules.index',
            [
                'breadcrumbs' => $breadcrumbs,
                'locale' => $locale,
                'workScedules' => $workScedules,
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->session()->forget('unauthorized_user');

        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['link' => "{$locale}/work-schedule", 'name' => "Work Schedules"],
            ['name' => "Add Work Schedule"]
        ];
        return view('admin.work_schedules.create', ['breadcrumbs' => $breadcrumbs, 'locale' => $locale]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWorkScheduleRequest $request)
    {
        $request->session()->forget('unauthorized_user');
        $storeWorkSchedule = (new StoreWorkSchedule())->execute($request->all());
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        if ($storeWorkSchedule) {
            Session::flash('success', 'Work Schedule is created successfully');
        } else {
            Session::flash('error', 'Work Schedule is creation Failed');
        }
        return redirect($locale . '/work-schedule')->with('locale', $locale);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($lang,Request $request, $id)
    {
        $request->session()->forget('unauthorized_user');

        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $workSchedule = (new GetWorkSchedule())->execute($id);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['link' => "{$locale}/work-schedule", 'name' => "Work Schedules"],
            ['name' => "Edit Work Schedule"]
        ];
        return view('admin.work_schedules.edit',
            [
                'breadcrumbs' => $breadcrumbs,
                'locale' => $locale,
                'workSchedule' => $workSchedule,
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update($lang,StoreWorkScheduleRequest $request, $id)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $deleteWorkSchedule = (new UpdateWorkSchedule())->execute($request->all(), $id);
        if ($deleteWorkSchedule) {
            Session::flash('success', 'Work Schedule is Updated successfully');
        } else {
            Session::flash('error', 'Work Schedule is Updating Failed');
        }
        return redirect($locale . '/work-schedule')->with('locale', $locale);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($lang,Request $request, $id)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $deleteWorkSchedule = (new DeleteWorkSchedule())->execute($id);
        if ($deleteWorkSchedule) {
            Session::flash('success', 'Work Schedule is Deleted successfully');
        } else {
            Session::flash('error', 'Work Schedule is Deletion Failed');
        }
        return redirect($locale . '/work-schedule')->with('locale', $locale);
    }
}
