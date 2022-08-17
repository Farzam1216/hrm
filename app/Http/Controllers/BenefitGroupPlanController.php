<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Benefit\Actions\CreateBenefitGroupPlan;
use App\Domain\Benefit\Actions\DeleteGroupPlan;
use App\Domain\Benefit\Actions\EditBenefitGroupPlan;
use App\Domain\Benefit\Actions\GetPlanDetail;
use App\Domain\Benefit\Actions\UpdateBenefitGroupPlan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;

class BenefitGroupPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store($lang, Request $request)
    {
        // $locale = $request->segment(1, ''); // `en` or `es`
        // App::setLocale($locale);
        // (new CreateBenefitGroupPlan())->execute($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Request  $request
     * @param  int  $groupId
     * @return \Illuminate\Http\Response
     */
    public function edit($locale, Request $request, $groupId)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, '');
        $data = (new EditBenefitGroupPlan)->execute($groupId);
        return view('admin.benefit-group.addExistingPlan')
            ->with('availableBenefitPlans', $data['availableBenefitPlans'])
            ->with('benefitGroup', $data['benefitGroup'])
            ->with('locale', $locale);
    }

    /**
     * Update the groupPln.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePlan(Request $request)
    {
        $locale = $request->segment(1, '');
        (new UpdateBenefitGroupPlan())->execute($request);
        Session::flash('success', trans('language.Benefit plan has been updated successfully'));
        return redirect($locale . '/benefitgroup')->with('locale', $locale);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new DeleteGroupPlan())->execute($request->all());
        Session::flash('success', trans('language.Benefit group plan has been deleted successfully'));
        return redirect($locale . '/benefitgroup')->with('locale', $locale);
    }
    /**
     * get Detail of plan to add in BenefitGroup.
     *
     * @param Request $request
     */
    public function getBenefitPlanAJAX(Request $request)
    {
        $benefitPlanDetail = (new GetPlanDetail())->execute($request);
        return response()->json($benefitPlanDetail);
    }
}
