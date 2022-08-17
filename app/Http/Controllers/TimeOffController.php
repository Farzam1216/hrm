<?php

namespace App\Http\Controllers;

use App;
use App\Domain\TimeOff\Actions\AddTimeOffPolicy;
use App\Domain\TimeOff\Actions\AssignPolicyToEmployees;
use App\Domain\TimeOff\Actions\CreateTimeOff;
use App\Domain\TimeOff\Actions\EditTimeOff;
use App\Domain\TimeOff\Actions\GetEmployeesByPolicy;
use App\Domain\TimeOff\Actions\ManageTimeOffTypesAndPoliciesPermissions;
use App\Domain\TimeOff\Actions\UpdatePolicy;
use App\Domain\TimeOff\Actions\ViewAllPoliciesOtherThanNullOrManual;
use App\Domain\TimeOff\Models\Policy;
use App\Domain\TimeOff\Models\PolicyLevel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;

class TimeOffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"],['link' => "javascript:void(0)", 'name' => "Time Off "], ['name' => "Time Off Policy"]
        ];

        $getPolicies = new ViewAllPoliciesOtherThanNullOrManual();
        $data = $getPolicies->execute();
        return view('admin.time_off.index')
            ->with('locale', $locale)
            ->with('breadcrumbs', $breadcrumbs)
            ->with('policies', $data['policy'])
            ->with('policyLevels', $data['policyLevel']
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"],['link' => "javascript:void(0)", 'name' => "Time Off"], ['link' => $locale."/timeoff/policy",'name' => "Time Off Policy"], ['name' => "Create"]
        ];

        $createTimeOff = new CreateTimeOff();
        $data = $createTimeOff->execute();
        return view('admin.time_off.create')
            ->with('locale', $locale)
            ->with('breadcrumbs', $breadcrumbs)
            ->with('policies', $data['policy'])
            ->with('TimeOffTypes', $data['TimeOffTypes']
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $addpolicy = new AddTimeOffPolicy();
        $addpolicy->execute($request->all());

        Session::flash('success', trans('language.Time Off Policy is created successfully'));
        return redirect()->route('policy.index', [$locale]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param $lang
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, $lang, $id)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['link' => "javascript:void(0)", 'name' => "Time Off"], ['link' => $locale."/timeoff/policy", 'name' => "Time Off Policy"], ['name' => "Edit"]
        ];

        $editTimeOff = new EditTimeOff();
        $data = $editTimeOff->execute($id);
        return view('admin.time_off.edit')
            ->with('locale', $locale)
            ->with('policy', $data['policy'])
            ->with('TimeOffTypes', $data['TimeOffTypes'])
            ->with('policyLevels', $data['policyLevel'])
            ->with('breadcrumbs', $breadcrumbs);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $lang
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function update(Request $request, $lang, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $updatePolicy = new UpdatePolicy();
        $updatePolicy->execute($request->all(), $id);

        Session::flash('success', trans('language.Time Off Policy is updated successfully'));
        return redirect()->route('policy.index', [$locale]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $lang
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function destroy(Request $request, $lang, $id)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        $levels = PolicyLevel::where('policy_id', $id)->get();
        foreach ($levels as $level) {
            $level->delete();
        }

        $policy = Policy::find($id);
        $policy->delete();
        $manageTimeOffTypes = new ManageTimeOffTypesAndPoliciesPermissions();
        $manageTimeOffTypes->execute("policy", $id, "delete");

        return redirect()->route('policy.index', [$locale]);
    }

    public function assignedEmployees(Request $request, $locale, $policy_id)
    {
        $employees = new GetEmployeesByPolicy();
        $employees = $employees->execute($policy_id);

        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['link' => "javascript:void(0)", 'name' => "Time Off"], ['link' => $locale."/timeoff/policy", 'name' => "Time Off Policy"], ['name' => $employees['policy']->policy_name . " Policy"]
        ];

        return view('admin.time_off.policy')
        ->with('assignedEmployees', $employees['assignedEmployees'])
        ->with('availableEmployees', $employees['availableEmployees'])
        ->with('policy', $employees['policy'])
        ->with('breadcrumbs', $breadcrumbs)
        ->with('title', $employees['policy']->policy_name . " Policy");
    }
    
    public function assign(Request $request, $locale, $policy_id)
    {
        $employees = new GetEmployeesByPolicy();
        $employees = $employees->execute($policy_id);

        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['link' => "javascript:void(0)", 'name' => "Time Off"], ['link' => $locale."/timeoff/policy", 'name' => "Time Off Policy"], ['link' => $locale.'/timeoff/policy/'.$policy_id.'/employees', 'name' => $employees['policy']->policy_name . " Policy"], ['name' => "Assign Policy"]
        ];

        return view('admin.time_off.assign')
        ->with('assignedEmployees', $employees['assignedEmployees'])
        ->with('availableEmployees', $employees['availableEmployees'])
        ->with('policy', $employees['policy'])
        ->with('breadcrumbs', $breadcrumbs)
        ->with('title', $employees['policy']->policy_name . " Policy");
    }

    public function storeAssignedEmployees(Request $request)
    {
            $policy = new AssignPolicyToEmployees();
            $policy->execute($request->all());

            Session::flash('success', 'Policy assigned to employee sucessfully');

        return response()->json($request->policy_id);
    }
}
