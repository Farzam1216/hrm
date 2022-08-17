<?php

namespace App\Http\Controllers;

use App\Domain\Benefit\Actions\AddAvailablePlanInBenefitGroup;
use App\Domain\Benefit\Actions\CreateBenefitGroup;
use App\Domain\Benefit\Actions\DestroyBenefitGroup;
use App\Domain\Benefit\Actions\EditBenefitGroup;
use App\Domain\Benefit\Actions\GetBenefitGroupPlanDetail;
use App\Domain\Benefit\Actions\SaveDuplicateOfBenefitGroup;
use App\Domain\Benefit\Actions\StoreBenefitGroup;
use App\Domain\Benefit\Actions\UpdateBenefitGroup;
use App\Domain\Benefit\Actions\ViewBenefitGroups;
use Doctrine\DBAL\Schema\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class BenefitGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            [ 'name' => "Settings"], ['link' => "$locale/benefitgroup", 'name' => "Benefits"], ['name' => "Benefit Groups"]
        ];
        $benefitGroups = (new ViewBenefitGroups())->execute();
        return view('admin.benefit-group.index')->with('benefitGroups', $benefitGroups)->with('locale', $locale)
            ->with('breadcrumbs', $breadcrumbs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['name' => "Settings"], ['link' => "$locale/benefitgroup", 'name' => "Benefits"],
            ['link' => "$locale/benefitgroup", 'name' => "Benefit Groups"],
            [ 'name' => "Add"]
        ];
        $data = (new CreateBenefitGroup())->execute();
        return view('admin.benefit-group.create')->with('employees', $data['employees'])
            ->with('benefitPlans', $data['benefitPlans'])
            ->with('locale', $locale)
            ->with('breadcrumbs', $breadcrumbs);
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
        (new StoreBenefitGroup())->execute($request);
        Session::flash('success', trans('Benefit group has been added successfully'));
        return redirect($locale . '/benefitgroup')->with('locale', $locale);
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
     * @param Request $request
     * @param $locale
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, $locale, $id)
    {
        $request->session()->forget('unauthorized_user');
        App::setLocale($locale);
        $breadcrumbs = [
            [ 'name' => "Settings"], ['link' => "$locale/benefitgroup", 'name' => "Benefits"],
            ['link' => "$locale/benefitgroup", 'name' => "Benefit Groups"],
            [ 'name' => "Edit"]
        ];
        $data = (new EditBenefitGroup())->execute($id);
        return view('admin.benefit-group.edit')->with('employeesInBenefitGroup', $data['employeesInBenefitGroup'])
            ->with('employeesNotInBenefitGroup', $data['employeesNotInBenefitGroup'])
            ->with('benefitGroup', $data['benefitGroup'])
            ->with('locale', $locale)
            ->with('breadcrumbs', $breadcrumbs);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $locale
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $locale, $id)
    {
        App::setLocale($locale);
        (new UpdateBenefitGroup())->execute($request, $id);
        Session::flash('success', trans('Benefit Group has been Updated successfully'));
        return redirect($locale . '/benefitgroup')->with('locale', $locale);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $lang
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function destroy($lang, Request $request, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $request->session()->forget('unauthorized_user');
        (new DestroyBenefitGroup())->execute($id);
        Session::flash('success', trans('language.Benefit group has been deleted successfully'));
        return redirect($locale . '/benefitgroup')->with('locale', $locale);
    }
    public function duplicateBenefitGroup(Request $request)
    {
        //display view for duplicate benefit group
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new SaveDuplicateOfBenefitGroup())->execute($request->id);
        Session::flash('success', trans('Benefit group has been duplicated successfully'));
        return redirect($locale . '/benefitgroup')->with('locale', $locale);
    }
    /**
     * get Detail of Group Plan
     *
     * @param Request $request
     */
    public function getGroupPlan(Request $request)
    {
        $benefitPlanDetail = (new GetBenefitGroupPlanDetail())->execute($request);
        return response()->json($benefitPlanDetail);
    }

    /**
     * Add available plans in Benefit Group
     * @param $request
     * @return success message
     */
    public function addAvailablePlan(Request $request)
    {
        $request->session()->forget('unauthorized_user');
        $locale = $request->segment(1, '');
        (new AddAvailablePlanInBenefitGroup())->execute($request);
        Session::flash('success', trans('Benefit plan has been updated successfully'));
        return redirect($locale . '/benefitgroup')->with('locale', $locale);
    }
}
