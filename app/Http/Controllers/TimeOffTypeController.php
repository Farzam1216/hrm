<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Employee\Models\Employee;
use App\Domain\TimeOff\Actions\DeleteTimeOffType;
use App\Domain\TimeOff\Actions\GetAllTimeOffType;
use App\Domain\TimeOff\Actions\GetTimeOffType;
use App\Domain\TimeOff\Actions\ManageTimeOffTypesAndPoliciesPermissions;
use App\Domain\TimeOff\Models\AssignTimeOffType;
use App\Domain\TimeOff\Models\Policy;
use App\Domain\TimeOff\Models\TimeOffType;
use App\Http\Requests\validateTimeoffType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;

class TimeOffTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "$locale/timeofftype", 'name' => "Settings"],[ 'name' => "Time Off "], ['name' => "Time Off Types"]
        ];
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $timeOff = (new GetAllTimeOffType())->execute();

        return view('admin.time_off_type.index')->with('timeOfftypes', $timeOff)->with('breadcrumbs', $breadcrumbs)->with('locale', $locale);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "$locale/timeofftype", 'name' => "Settings"],[ 'name' => "Time Off "], ['link' => "$locale/timeofftype",'name' => "Time Off Types"], ['name' => "Create Time Off Type"]
        ];
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.


        return view('admin.time_off_type.create')->with('breadcrumbs', $breadcrumbs)->with('locale', $locale);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param validateTimeoffType $request
     * @return Response
     */
    public function store(validateTimeoffType $request)
    {
        $time_off_type_exist = TimeOffType::where('time_off_type_name', $request->timeOffType)->first();
        if ($time_off_type_exist == null) {
            $time_off_type= TimeOffType::create([
                'time_off_type_name' => $request->timeOffType,
            ]);
            $manageTimeOffTypes = new ManageTimeOffTypesAndPoliciesPermissions();
            $manageTimeOffTypes->execute("timeofftype", $time_off_type->id, "create");
            // Add none and Manual accrual options after assigning anyother policy to employee
            $addNonePolicy = Policy::create([
                'policy_name' => 'None',
                'first_accrual' => 'None',
                'carry_over_date' => '1st January',
                'accrual_happen' => 'At the begining of the period',
                'time_off_type' => $time_off_type->id
            ]);
            $addManualPolicy = Policy::create([
                'policy_name' => 'Manual Updated Balance',
                'first_accrual' => 'Manual',
                'carry_over_date' => '1st January',
                'accrual_happen' => 'At the begining of the period',
                'time_off_type' => $time_off_type->id
            ]);
            // Assign default none to each employee after creating time off type
            $assignDefaultNonePolicy = Employee::with('timeofftypes')->get();
            foreach ($assignDefaultNonePolicy as $assignDefaultPolicy) {
                $assignDefaultPolicy = AssignTimeOffType::create([
                    'employee_id' => $assignDefaultPolicy->id,
                    'type_id' => $time_off_type->id,
                    'accrual_option' => $addNonePolicy->policy_name,
                    'attached_policy_id' => $addNonePolicy->id,
                ]);
            }
            Session::flash('success', trans('language.Time Off Type is created successfully'));
        } else {
            Session::flash('error', trans('language.Time Off Type with this name already exist'));
        }
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        return redirect($locale.'/time-off-type')->with('locale', $locale);
    }

    public function edit($lang,Request $request,$id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "$locale/timeofftype", 'name' => "Settings"],[ 'name' => "Time Off "], ['link' => "$locale/timeofftype",'name' => "Time Off Types"], ['name' => "Update Time Off Type"]
        ];
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.

        $timeOffType = (new GetTimeOffType())->execute($id);

        return view('admin.time_off_type.edit')->with('timeOffType', $timeOffType)->with('breadcrumbs', $breadcrumbs)->with('locale', $locale);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update($lang, validateTimeoffType $request, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $timeOffType = TimeOffType::find($id);
        $timeOffType->time_off_type_name = $request->timeOffType;
        $timeOffType->save();
        Session::flash('success', trans('language.Time Off Type is Updated successfully'));

        return redirect($locale.'/time-off-type')->with('locale', $locale);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($lang, Request $request, $id)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        (new DeleteTimeOffType())->execute($id);
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        Session::flash('success', trans('language.Time Off Type deleted successfully'));

        return redirect($locale.'/time-off-type')->with('locale', $locale);
    }
}
