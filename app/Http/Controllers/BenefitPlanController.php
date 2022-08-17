<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Benefit\Actions\CreateBenefitPlan;
use App\Domain\Benefit\Actions\GetBenefitPlanType;
use App\Domain\Benefit\Actions\DeleteBenefitPlan;
use App\Domain\Benefit\Actions\EditBenefitPlan;
use App\Domain\Benefit\Models\BenefitPlan;
use App\Domain\Benefit\Actions\SaveDuplicateOfBenefitPlan;
use App\Domain\Benefit\Actions\StoreBenefitPlan;
use App\Domain\Benefit\Actions\UpdateBenefitPlan;
use App\Domain\Benefit\Actions\ViewBenefitPlan;
use App\Http\Requests\validateBenefitPlanForAdmin;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use Session;

class BenefitPlanController extends Controller
{
    public function index(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $benefitPlanType = (new ViewBenefitPlan())->execute();
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "javascript:void(0)", 'name' => "Settings"], ['name' => "Benefits"], ['name' => "Plans"]
        ];
        return view('admin.benefit_plan.index')->with('benefitPlanType', $benefitPlanType)
            ->with('locale', $locale)->with('breadcrumbs', $breadcrumbs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $lang
     * @param Request $request
     * @param $planType
     * @return Response
     */
    public function create($lang, Request $request, $planType)
    {

        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "$locale/benefit-plan", 'name' => "Settings"],['link' => "#", 'name' => "Benefits"],['link' => "$locale/benefit-plan", 'name' => "Plans"], ['name' => "Add Plan"]
        ];
        $benefitViewFields = (new CreateBenefitPlan)->execute($request, $planType);
        return view('admin.benefit_plan.create')->with('locale', $locale)
            ->with('benefitViewFields', $benefitViewFields['benefitFields'])
            ->with('currencies', $benefitViewFields['currency'])
            ->with('planType', $planType)
            ->with('breadcrumbs', $breadcrumbs);
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function store(validateBenefitPlanForAdmin $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new StoreBenefitPlan())->execute($request->all());
        Session::flash('success', trans('Benefit Plan is Added successfully'));
        return redirect($locale . '/benefit-plan')->with('locale', $locale);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $lang
     * @param $type
     * @param Request $request
     * @param $id
     * @return void
     */
    public function edit($lang, $type, Request $request, $id)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "$locale/benefit-plan", 'name' => "Settings"],['link' => "$locale/benefit-plan", 'name' => "Benefits"], ['link' => "$locale/benefit-plan", 'name' => "Plans"],['name' => "Edit Plan"]
        ];
        $benefitPlanData = (new EditBenefitPlan())->execute($type, $id);
        return view('admin.benefit_plan.edit')->with('locale', $locale)
            ->with('benefitPlan', $benefitPlanData['benefitPlan'])
            ->with('dateRange', $benefitPlanData['dateRange'])
            ->with('id', $id)
            ->with('planCoveragesName', json_encode($benefitPlanData['planCoveragesName']))
            ->with('planType', $benefitPlanData['planType'])
            ->with('currencies', $benefitPlanData['currency'])
            ->with('planCoverages', $benefitPlanData['planCoverages'])
            ->with('breadcrumbs', $breadcrumbs);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update($lang, Request $request, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new UpdateBenefitPlan())->execute($request->all(), $id);
        Session::flash('success', trans('language.Benefit Plan is Updated successfully'));
        return redirect($locale . '/benefit-plan')->with('locale', $locale);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $locale
     * @param int $id
     * @return Response
     */
    public function destroy($locale, $id)
    {
        session()->forget('unauthorized_user');
        (new DeleteBenefitPlan())->execute($id);
        // $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        Session::flash('success', trans('language.Benefit Plan has been deleted successfully.'));
        return redirect($locale . '/benefit-plan')->with('locale', $locale);
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function duplicateBenefitPlan($locale,Request $request,$id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "$locale/benefit-plan", 'name' => "Settings"],['link' => "$locale/benefit-plan", 'name' => "Benefits"], ['link' => "$locale/benefit-plan", 'name' => "Plans"],['name' => "Duplicate Plan"]
        ];
        $request->session()->forget('unauthorized_user');
        $planType = (new GetBenefitPlanType())->execute($id);
        //display view for duplicate benefit plan
        return view('admin.benefit_plan.duplicatePlan')->with('locale', $locale)->with('planType', $planType)->with('breadcrumbs', $breadcrumbs);
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function saveDuplicateBenefitPlan(Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new SaveDuplicateOfBenefitPlan())->execute($request);
        Session::flash('success', trans('language.Benefit Plan is Duplicated Successfully'));
        return redirect($locale . '/benefit-plan')->with('locale', $locale);
    }
}
