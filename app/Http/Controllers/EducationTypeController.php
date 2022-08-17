<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Employee\Actions\CreateEducationType;
use App\Domain\Employee\Actions\DeleteEducationType;
use App\Domain\Employee\Actions\GetAllEducationTypes;
use App\Domain\Employee\Actions\GetEducationTypeByID;
use App\Domain\Employee\Actions\UpdateEducationType;
use App\Domain\Employee\Actions\StoreEducationTypeNotification;
use App\Domain\Employee\Actions\StoreEducationTypeUpdateNotification;
use App\Domain\Employee\Actions\StoreEducationTypeDeleteNotification;
use App\Http\Requests\validateEducationType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;

class EducationTypeController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $educationTypes = (new GetAllEducationTypes())->execute();
        $breadcrumbs = [
            ['link' => "$locale/education-types", 'name' => "Settings | Types | "], ['name' => "Education Types"]
        ];
        return view('admin.education_types.index')->with('educationTypes', $educationTypes)
            ->with('locale', $locale)->with('breadcrumbs', $breadcrumbs);
    }

    /**
     * Display create page to store new resource
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "$locale/education-types", 'name' => "Settings | Types | Education Types |"], ['name' => "Add"]
        ];
        return view('admin.education_types.create', ['breadcrumbs' => $breadcrumbs, 'locale' => $locale]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws ValidationException
     */
    public function store($lang, validateEducationType $request)
    {
        $request->session()->forget('unauthorized_user');
        (new StoreEducationTypeNotification())->execute($request);
        (new CreateEducationType())->execute($request);
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);

        return redirect($locale.'/education-types')->with('locale', $locale);
    }
    /**
     * Display edit page to edit resource
     * @param Request $request
     * @param $id
     * @param $lang
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($lang, $id, Request $request)
    {
        $request->session()->forget('unauthorized_user');  //Hide unauthorized user information menu.
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        $breadcrumbs = [
            ['link' => "$locale/education-types", 'name' => "Settings | Types | Education Types |"], ['name' => "Edit"]
        ];
        $education_type = (new GetEducationTypeByID())->execute($id);
        return view('admin.education_types.edit',
            ['education_type' => $education_type,
                'breadcrumbs' => $breadcrumbs,
                'locale' => $locale,
            ]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param $lang
     * @param Request $request
     * @param $id
     *
     * @return RedirectResponse
     *
     * @throws ValidationException
     */
    public function update($lang, validateEducationType $request, $id)
    {
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        //TODO:: Add Form Requests
        (new StoreEducationTypeUpdateNotification())->execute($request,$id);
        (new UpdateEducationType())->execute($id, $request);

        return redirect($locale.'/education-types')->with('locale', $locale);
    }

    /** display types
     *
     */
    public function show()
    {
    }

    /**$id
     * @return Resp
     * Remove the specified resource from storage.
     *
     * @param int onse
     */
    public function destroy($lang, Request $request, $id)
    {
        $request->session()->forget('unauthorized_user');
        (new StoreEducationTypeDeleteNotification())->execute($id);
        (new DeleteEducationType())->execute($id);
        $locale = $request->segment(1, ''); // `en` or `es`
        App::setLocale($locale);
        return redirect($locale.'/education-types')->with('locale', $locale);
    }
}
