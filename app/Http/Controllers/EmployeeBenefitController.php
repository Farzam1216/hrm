<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Benefit\Actions\CreateEmployeeBenefit;
use App\Domain\Benefit\Actions\StoreEmployeeBenefit;
use App\Domain\Benefit\Actions\ViewEmployeeBenefit;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Session;

class EmployeeBenefitController extends Controller
{
    public function index(Request $request)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        $userId = $request->segment(3, Auth::id());
        App::setLocale($locale);
        $data = (new ViewEmployeeBenefit())->execute($userId, $this);
        return view('admin.employee_benefit.index')
            ->with('employeeBenefitStatus', $data['employeeBenefitStatus']->sortBy('updated_at'))->with('employeeBenefitDetails', $data['employeeBenefitDetails'])
            ->with('locale', $locale)->with('permissions', $data['permissions']);
    }

    public function create($lang, $data)
    {
        App::setLocale($lang);
        $data = (new CreateEmployeeBenefit())->execute($data, $this);
        $attributes = $data['attributes'];
        return view('admin.employee_benefit.create')->with([
            'locale'    => $lang, 'benefitData' => $data['benefitData'], 'employeeID' => $attributes[0], 'groupPlanID' => $attributes[1], 'status' => $attributes[2],
            'currencies' => $data['currencies'], 'employeeBenefitStatus' => $data['employeeBenefitStatus']
        ]);
    }

    /**
     * Store Employee Benefit Status
     * @param $locale
     * @param Request $request
     * @return RedirectResponse
     */

    public function store($locale, Request $request)
    {
        (new StoreEmployeeBenefit())->execute($request);
        Session::flash('success', trans('language.Benefit Status is updated successfully'));
        return Redirect::to(url($locale . '/employees/' . $request->employee_ID . '/benefit-details'))->with('locale', $locale);
    }
}
