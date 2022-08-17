<?php

namespace App\Http\Controllers;

use App;
use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\storePaySchedule as storePayScheduleValidation;
use App\Http\Requests\updatePaySchedule as updatePayScheduleValidation;
use App\Domain\PayRoll\Actions\GetPaySchedules;
use App\Domain\PayRoll\Actions\EditPaySchedule;
use App\Domain\PayRoll\Actions\StorePaySchedule;
use App\Domain\PayRoll\Actions\UpdatePayDateById;
use App\Domain\PayRoll\Actions\UpdatePayScheduleById;
use App\Domain\PayRoll\Actions\DestoryPayScheduleById;
use App\Domain\PayRoll\Actions\GetPayDatesByPaySchedule;
use App\Domain\PayRoll\Actions\StorePayScheduleAssignment;
use App\Domain\PayRoll\Actions\GetEmployeesWithPaySchedule;

class PayScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('unauthorized_user');

        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Payroll Management"], ['name' => "Pay Schedules"]
         ];

         $data = (new GetPaySchedules())->execute();
         return view('admin.pay-schedule.index')
         ->with('locale', $locale)
         ->with('breadcrumbs', $breadcrumbs)
         ->with('permissions', $data['permissions'])
         ->with('paySchedules', $data['paySchedules']);
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
            ['link' => "javascript:void(0)", 'name' => "Payroll Management"], ['link' => route('pay-schedule.index', [$locale]), 'name' => "Pay Schedules"], ['name' => "Create"]
         ];

         return view('admin.pay-schedule.create')
         ->with('locale', $locale)
         ->with('now', Carbon::now()->format('d-m-Y'))
         ->with('breadcrumbs', $breadcrumbs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(storePayScheduleValidation $request)
    {
        $request->session()->forget('unauthorized_user');

        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $data = (new StorePaySchedule())->execute($request);

        if ($data) {
            Session::flash('success', trans('language.Pay schedule is stored successfully'));
            return redirect()->route('pay-schedule.index', [$locale]);
        } else {
            Session::flash('error', trans('language.Something went wrong while storing pay schedule'));
            return back();
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
        $request->session()->forget('unauthorized_user');

        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Payroll Management"], ['link' => route('pay-schedule.index', [$locale]), 'name' => "Pay Schedules"], ['name' => "Edit"]
         ];

        $paySchedule = (new EditPaySchedule())->execute($id);

         return view('admin.pay-schedule.edit')
         ->with('locale', $locale)
         ->with('paySchedule', $paySchedule)
         ->with('breadcrumbs', $breadcrumbs);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(updatePayScheduleValidation $request, $locale, $id)
    {
        $request->session()->forget('unauthorized_user');

        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $data = (new UpdatePayScheduleById())->execute($request, $id);

        if ($data) {
            Session::flash('success', trans('language.Pay schedule is updated successfully'));
            return redirect()->route('pay-schedule.index', [$locale]);
        } else {
            Session::flash('error', trans('language.Something went wrong while updating pay schedule'));
            return back();
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
        $request->session()->forget('unauthorized_user');

        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $data = (new DestoryPayScheduleById())->execute($id);

        if ($data) {
            Session::flash('success', trans('language.Pay schedule is deleted successfully'));
            return redirect()->route('pay-schedule.index', [$locale]);
        } else {
            Session::flash('error', trans('language.Something went wrong while deleting pay schedule'));
            return back();
        }
    }

    public function assign(Request $request, $locale, $id)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Payroll Management"], ['link' => route('pay-schedule.index', [$locale]), 'name' => "Pay Schedules"], ['name' => "Assign"]
         ];

        $data = (new GetEmployeesWithPaySchedule())->execute($id);

         return view('admin.pay-schedule.assign')
         ->with('locale', $locale)
         ->with('breadcrumbs', $breadcrumbs)
         ->with('employees', $data['employees'])
         ->with('pay_schedule', $data['pay_schedule']);
    }

    public function submitAssignment(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $data = (new StorePayScheduleAssignment)->execute($request);

        if ($data['employee'] == true && $data['check'] == 'assigned') {
            Session::flash('success', 'Pay Schedule is assigned to employees successfully');
        }
        if ($data['employee'] == true && $data['check'] == 'updated') {
            Session::flash('success', 'Pay Schedule assignment is updated successfully');
        }

        return response()->json($data);
    }

    public function getPayDatesByAJAX(Request $request)
    {
        $data = (new GetPayDatesByPaySchedule)->execute($request);

        return response()->json($data);
    }

    public function updatePayDateByAJAX(Request $request)
    {
        $data = (new UpdatePayDateById)->execute($request);

        return response()->json($data);
    }
}
