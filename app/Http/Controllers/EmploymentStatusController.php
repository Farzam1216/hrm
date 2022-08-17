<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Employee\Actions\CreateEmploymentStatus;
use App\Domain\Employee\Actions\DeleteEmploymentStatus;
use App\Domain\Employee\Actions\GetAllEmploymentStatus;
use App\Domain\Employee\Actions\UpdateEmploymentStatus;
use App\Domain\Employee\Actions\GetEmploymentStatusByIDWithEmployees;
use App\Domain\Employee\Models\EmploymentStatus;
use App\Domain\Hiring\Actions\StoreEmploymentStatusNotification;
use App\Domain\Hiring\Actions\StoreEmploymentStatusUpdateNotification;
use App\Domain\Hiring\Actions\StoreEmploymentStatusDeleteNotification;
use App\Http\Requests\validateEmploymentStatus;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class EmploymentStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return Factory|View
     */
    public function index(Request $request, EmploymentStatus $employmentStatus)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            [ 'name' => "Settings  "], [ 'name' => "Job  "], ['link' => "$locale/employment-status",'name' => "Employment Status"]
        ];
        $employmentStatus = (new GetAllEmploymentStatus())->execute();
        return view('admin.employment_status.index')->with('employmentStatuses', $employmentStatus)->with('locale', $locale)->with('breadcrumbs', $breadcrumbs);
    }

    public function create(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['name' => "Settings"],[ 'name' => "Job "],['link' => "$locale/employment-status", 'name' => " Employment Status "], ['name' => "Add"]
        ];
        return view('admin.employment_status.create', ['breadcrumbs' => $breadcrumbs, 'locale' => $locale]);
    }

    public function edit(Request $request, $lang, $id)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['name' => "Settings"],[ 'name' => "Job "],['link' => "$locale/employment-status", 'name' => " Employment Status "], ['name' => "Edit"]
        ];
        $employmentstatus = (new GetEmploymentStatusByIDWithEmployees())->execute($id);
        return view('admin.employment_status.edit',
            [   'employmentstatus' => $employmentstatus,
                'breadcrumbs' => $breadcrumbs,
                'locale' => $locale,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param validateEmploymentStatus $request
     * @return RedirectResponse|Redirector
     */
    public function store(validateEmploymentStatus $request)
    {
        (new CreateEmploymentStatus())->execute($request);
        (new StoreEmploymentStatusNotification())->execute($request);    
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        return redirect($locale.'/employment-status')->with('locale', $locale);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     *
     * @return RedirectResponse
     *
     * @throws ValidationException
     */
    public function update(validateEmploymentStatus $request, $lang, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new StoreEmploymentStatusUpdateNotification())->execute($request,$id);    
        (new UpdateEmploymentStatus())->execute($id, $request);
        return redirect($locale.'/employment-status')->with('locale', $locale);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $lang, $id)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.

        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        (new StoreEmploymentStatusDeleteNotification())->execute($id);
        (new DeleteEmploymentStatus())->execute($id);

        return redirect($locale.'/employment-status')->with('locale', $locale);
    }
}
